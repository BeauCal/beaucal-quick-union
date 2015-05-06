<?php

namespace BeaucalQuickUnion\Adapter;

use BeaucalQuickUnion\Adapter\AdapterInterface as UnionAdapterInterface;
use BeaucalQuickUnion\Order\Strategy;
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

    public function beginTransaction() {
        $this->options->getUseTransactions() and $this->getConnection()->beginTransaction();
    }

    public function commit() {
        $this->options->getUseTransactions() and $this->getConnection()->commit();
    }

    public function rollback() {
        $this->options->getUseTransactions() and $this->getConnection()->rollback();
    }

    protected function getConnection() {
        return $this->gateway->getAdapter()->getDriver()->getConnection();
    }

    public function union(DirectedOrder $order) {
        list($item['item'], $set['set']) = $order->getOrder();
        if ($order instanceof Strategy\Flatten) {
            $this->gateway->update($set, $item);
            return;
        }
        $this->gateway->insert($item + $set);
    }

    /**
     * @param string $item
     * @return mixed        string or null
     */
    public function getSet($item) {
        $resultSet = $this->gateway->select(['item' => $item]);
        return $resultSet->count() ? $resultSet->current()->set : null;
    }

}
