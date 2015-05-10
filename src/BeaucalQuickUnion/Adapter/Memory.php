<?php

namespace BeaucalQuickUnion\Adapter;

use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
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

    /**
     * @param string $item
     */
    public function insert($item) {
        $this->data[$item] = $item;
    }

    /**
     * @param string $item1
     * @param string $item2
     */
    public function setParent($item1, $item2) {
        $this->data[$item1] = $item2;
    }

    /**
     * @param string $item
     * @return mixed        string or null
     */
    public function getParent($item) {
        return isset($this->data[$item]) ? $this->data[$item] : null;
    }

}
