<?php

namespace BeaucalQuickUnion\Adapter;

use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\AbstractOptions;

class Db implements UnionAdapterInterface {

    /**
     * @var TableGateway
     */
    protected $gateway;

    /**
     * @var AbstractOptions
     */
    protected $options;

    public function __construct(TableGateway $gateway, AbstractOptions $options) {
        $this->gateway = $gateway;
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
        $this->gateway->insert([
            'item' => $item, 'set' => $item
        ]);
    }

    /**
     * @param string $item1
     * @param string $item2
     */
    public function setParent($item1, $item2) {
        $update['set'] = $item2;
        $where['item'] = $item1;
        $this->gateway->update($update, $where);
    }

    /**
     * @param string $item
     * @return mixed        string or null
     */
    public function getParent($item) {
        $resultSet = $this->gateway->select(['item' => $item]);
        return $resultSet->count() ? $resultSet->current()->set : null;
    }

}
