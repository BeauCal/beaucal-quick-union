<?php

namespace BeaucalQuickUnion\Order;

/**
 * Random order keeps union tree balanced.
 */
class Set extends AbstractOrder {

    protected $isShuffled = false;

    /**
     * @return string[]
     */
    public function getOrder() {

        /**
         * Order across multiple calls is preserved.
         */
        if (!$this->isShuffled) {
            $items = [$this->item1, $this->item2];
            shuffle($items);
            list($this->item1, $this->item2) = $items;
            $this->isShuffled = true;
        }
        return [$this->item1, $this->item2];
    }

}
