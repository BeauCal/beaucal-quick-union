<?php

namespace BeaucalQuickUnion\Service;

use BeaucalQuickUnion\Order\Strategy;
use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
use BeaucalQuickUnion\Exception\LoopException;
use BeaucalQuickUnion\Options\Union as UnionOptions;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\LoggerAwareTrait;

class Union implements LoggerAwareInterface {

    use LoggerAwareTrait;

    /**
     * @var UnionAdapterInterface
     */
    protected $adapter;

    /**
     * @var UnionOptions
     */
    protected $options;

    /**
     * @param UnionAdapterInterface $adapter
     */
    public function __construct(
    UnionAdapterInterface $adapter, UnionOptions $options
    ) {
        $this->adapter = $adapter;
        $this->options = $options;
    }

    /**
     * @return UnionOptions
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * @param Strategy\Directed $order
     */
    public function union(Strategy\Directed $order) {

        /**
         * Find sets (which also ensures each has a record).
         */
        list($item1, $item2) = $order->getOrder();
        $set1 = $this->query($item1);
        $set2 = $this->query($item2);
        if ($set1 == $set2) {
            return;
        }

        $this->adapter->union(new Strategy\Flatten($set1, $set2));
    }

    /**
     * @param string $item
     * @return mixed         String set identifier or null on blank input
     */
    public function query($item) {
        $item = (string) $item;
        if (strlen($item) == 0) {
            return null;
        }

        /**
         * Root.
         */
        $set = $this->adapter->getSet($item);
        if (strlen($set) == 0) {
            $this->adapter->union(new Strategy\Directed($item, $item));
            $set = $item;
        }
        if ($set == $item) {
            return $set;
        }

        /**
         * Non-root.
         */
        return $this->queryInternal($set);
    }

    /**
     * @param string $item
     * @return mixed         String set identifier or null on blank input
     */
    protected function queryInternal($item) {
        $set = $item;

        /**
         * Follow the path and flatten as we go.
         * A loop -- which is VERY BAD -- will be detected.
         */
        for (;;) {
            $parent = $this->adapter->getSet($set);
            if ($parent == $set) {
                break;
            }

            /**
             * Loop found -- VERY BAD.  Can be bandaged up.
             */
            $grandparent = $this->adapter->getSet($parent);
            if ($grandparent == $set) {
                $alert = __CLASS__ . ": {$set} is its own grandparent";
                $this->getLogger() and $this->getLogger()->alert($alert);

                if (!$this->options->getLoopDamageControl()) {
                    throw new LoopException($alert);
                }
                $this->adapter->union(new Strategy\Flatten($set, $set));
                break;
            }
            $this->adapter->union(new Strategy\Flatten($set, $grandparent));
            $set = $grandparent;
        }
        return $set;
    }

    /**
     * @return mixed string or null
     */
    public function getAdapterClass() {
        return $this->adapter ? get_class($this->adapter) : null;
    }

}
