<?php

namespace BeaucalQuickUnion\Adapter;

use BeaucalQuickUnion\Order\Strategy\Directed as DirectedOrder;

interface AdapterInterface {

    public function getOptions();

    public function beginTransaction();

    public function commit();

    public function rollback();

    public function union(DirectedOrder $order);

    /**
     * @param string $item
     * @return mixed        non-empty string or null
     */
    public function getSet($item);
}
