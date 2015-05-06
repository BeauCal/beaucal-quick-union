<?php

namespace BeaucalQuickUnion\Order\Strategy;

use BeaucalQuickUnion\Exception\RuntimeException;

/**
 * Directed item1->item2 ordering.
 */
class Directed {

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

    /**
     * @return string[]
     */
    public function getOrder() {
        return [$this->item1, $this->item2];
    }

    protected function invariant() {
        if (!strlen($this->item1) || !strlen($this->item2)) {
            throw new RuntimeException('item is blank');
        }
        if (!is_string($this->item1) || !is_string($this->item2)) {
            // @codeCoverageIgnoreStart
            throw new RuntimeException('item is not a string');
        }
        // @codeCoverageIgnoreEnd
    }

}
