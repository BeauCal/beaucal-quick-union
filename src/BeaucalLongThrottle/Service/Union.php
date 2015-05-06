<?php

namespace BeaucalQuickUnion\Service;

use BeaucalQuickUnion\Order\Strategy\AbstractOrder;
use BeaucalQuickUnion\Order\Strategy\Directed;
use BeaucalQuickUnion\Order\Strategy\Flatten;
use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
use BeaucalQuickUnion\Exception\RuntimeException;

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
     * @param AbstractOrder $order
     */
    public function union(AbstractOrder $order) {

        /**
         * Find sets (which also ensures each has a record).
         */
        $set1 = $this->query($order->getOrder()[0]);
        $set2 = $this->query($order->getOrder()[1]);
        if ($set1 == $set2) {
            return;
        }

        $this->adapter->union(new Directed($set1, $set2));
    }

    /**
     * @param string $item
     * @return string       Set identifier
     */
    public function query($item) {
        if (strlen($item) == 0) {
            throw new RuntimeException('query must be given a value');
        }

        /**
         * Item is root.
         */
        $set = $this->adapter->getSet($item);
        if (strlen($set) == 0) {
            $this->adapter->union(new Directed($item, $item));
            $set = $item;
        }
        if ($set == $item) {
            return $set;
        }

        /**
         * Follow the path, flatten a little as we go.
         */
        $setParent = $this->query($set);
        $this->adapter->union(new Flatten($item, $setParent));
        return $this->query($setParent);
    }

}
