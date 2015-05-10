<?php

namespace BeaucalQuickUnion\Service;

use BeaucalQuickUnion\Order\AbstractOrder;
use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
use BeaucalQuickUnion\Exception\LoopException;
use BeaucalQuickUnion\Options\Union as UnionOptions;
use BeaucalQuickUnion\Exception;
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
     * Provide one AbstractOrder or two strings.
     *
     * @param mixed $item1  string or AbstractOrder
     * @param mixed $item2  string or null
     */
    public function union($item1, $item2 = null) {

        /**
         * Determine order.
         */
        if ($item1 instanceof AbstractOrder) {
            $order = $item1;
        } else {
            $orderClass = $this->options->getOrderClass();
            if (!is_subclass_of(
            $orderClass, 'BeaucalQuickUnion\Order\AbstractOrder'
            )) {
                throw new Exception\OptionException('invalid order_class');
            }
            $order = new $orderClass($item1, $item2);
        }

        /**
         * Find sets (which also ensures each has a record).
         */
        $set1 = $this->query($order->getOrder()[0]);
        $set2 = $this->query($order->getOrder()[1]);
        if ($set1 == $set2) {
            return;
        }

        $this->adapter->setParent($set1, $set2);
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

        $set = $this->adapter->getParent($item);
        if (strlen($set) == 0) {
            $this->adapter->insert($item);
            $set = $item;
        }
        return ($set == $item) ? $set : $this->queryInternal($set);
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
            $parent = $this->adapter->getParent($set);
            if ($parent == $set) {
                break;
            }

            /**
             * Loop found -- VERY BAD.  Can be bandaged up.
             */
            $grandparent = $this->adapter->getParent($parent);
            if ($grandparent == $set) {
                $alert = __CLASS__ . ": {$set} is its own grandparent";
                $this->getLogger() and $this->getLogger()->alert($alert);

                if (!$this->options->getLoopDamageControl()) {
                    throw new LoopException($alert);
                }
                $this->adapter->setParent($set, $set);
                break;
            }
            $this->adapter->setParent($set, $grandparent);
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
