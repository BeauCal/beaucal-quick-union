<?php

namespace BeaucalQuickUnion\Service;

use BeaucalQuickUnion\Order\Strategy;
use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;

class Union {

    /**
     * @var UnionAdapterInterface
     */
    protected $adapter;

    /**
     * @param UnionAdapterInterface $adapter
     */
    public function __construct(UnionAdapterInterface $adapter) {
        $this->adapter = $adapter;
    }

    /**
     * @param Strategy\Directed $order
     */
    public function union(Strategy\Directed $order) {

        /**
         * Find sets (which also ensures each has a record).
         */
        $set1 = $this->query($order->getOrder()[0]);
        $set2 = $this->query($order->getOrder()[1]);
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
         * Not root, so flatten path as we go.
         */
        $setParent = $this->adapter->getSet($set);
        $this->adapter->union(new Strategy\Flatten($item, $setParent));
        return $this->query($setParent);
    }

}
