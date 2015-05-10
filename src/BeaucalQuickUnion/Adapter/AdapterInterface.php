<?php

namespace BeaucalQuickUnion\Adapter;

interface AdapterInterface {

    public function getOptions();

    /**
     * New item: points to itself.
     *
     * @param string $item
     */
    public function insert($item);

    /**
     * Existing items: item1 points to item2.
     *
     * @param string $item1
     * @param string $item2
     */
    public function setParent($item1, $item2);

    /**
     * @param string $item
     * @return mixed        non-empty string or null
     */
    public function getParent($item);
}
