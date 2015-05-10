<?php

namespace BeaucalQuickUnion\Adapter;

use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
use BeaucalQuickUnion\Order\Strategy;
use Zend\Stdlib\AbstractOptions;

/**
 * Provides union in-memory, without persisting between processes.
 */
class Memory implements UnionAdapterInterface {

    /**
     * @var AbstractOptions
     */
    protected $options;

    /**
     * @var array
     */
    protected $data = [];

    public function __construct(AbstractOptions $options) {
        $this->options = $options;
    }

    /**
     * @return AbstractOptions
     */
    public function getOptions() {
        return $this->options;
    }

    public function union(Strategy\Directed $order) {
        list($item, $set) = $order->getOrder();
        $this->data[$item] = $set;
    }

    /**
     * @param string $item
     * @return mixed        string or null
     */
    public function getSet($item) {
        return isset($this->data[$item]) ? $this->data[$item] : null;
    }

}
