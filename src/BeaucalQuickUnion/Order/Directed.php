<?php

namespace BeaucalQuickUnion\Order;

/**
 * Directed item1->item2 ordering.
 */
class Directed extends AbstractOrder {

    /**
     * @return string[]
     */
    public function getOrder() {
        return [$this->item1, $this->item2];
    }

}
