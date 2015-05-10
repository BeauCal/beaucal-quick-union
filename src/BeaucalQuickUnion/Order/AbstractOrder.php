<?php

namespace BeaucalQuickUnion\Order;

use BeaucalQuickUnion\Exception\RuntimeException;

abstract class AbstractOrder {

    /**
     * @var string
     */
    protected $item1;
    protected $item2;

    /**
     * @param string $item1
     * @param string $item2
     */
    public function __construct($item1, $item2) {
        $this->item1 = (string) $item1;
        $this->item2 = (string) $item2;
        $this->invariant();
    }

    protected function invariant() {
        if (!strlen($this->item1) || !strlen($this->item2)) {
            throw new RuntimeException('item is blank');
        }
    }

}
