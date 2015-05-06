<?php

namespace BeaucalQuickUnion\Order\Strategy;

/**
 * Random order keeps union tree balanced.
 * Performs union in true sense of the word.
 */
class Random extends Directed {

    protected $isShuffled = false;

    /**
     * @return string[] Shuffled
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
