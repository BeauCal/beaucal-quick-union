<?php

namespace BeaucalQuickUnion\Adapter;

use BeaucalQuickUnion\Order\Strategy;

interface AdapterInterface {

    public function getOptions();

    public function union(Strategy\Directed $order);

    /**
     * @param string $item
     * @return mixed        non-empty string or null
     */
    public function getSet($item);
}
