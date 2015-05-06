<?php

namespace BeaucalQuickUnion\Options;

use Zend\Stdlib\AbstractOptions;

class DbAdapter extends AbstractOptions {

    protected $useTransactions = true;
    protected $dbAdapterClass = 'Zend\Db\Adapter\Adapter';
    protected $dbTable = 'beaucal_union';
    protected $defaultOrderStrategyClass = 'BeaucalQuickUnion\Order\Strategy\Directed';

    /**
     * @return bool
     */
    public function getUseTransactions() {
        return $this->useTransactions;
    }

    /**
     * @param bool $useTransactions
     * @return DbAdapter
     */
    public function setUseTransactions($useTransactions) {
        $this->useTransactions = $useTransactions;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbAdapterClass() {
        return $this->dbAdapterClass;
    }

    /**
     * @param string $dbAdapterClass
     * @return DbAdapter
     */
    public function setDbAdapterClass($dbAdapterClass) {
        $this->dbAdapterClass = $dbAdapterClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getDbTable() {
        return $this->dbTable;
    }

    /**
     * @param string $dbTable
     * @return DbAdapter
     */
    public function setDbTable($dbTable) {
        $this->dbTable = $dbTable;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultOrderStrategyClass() {
        return $this->defaultOrderStrategyClass;
    }

    /**
     * @param string $defaultOrderStrategyClass
     * @return DbAdapter
     */
    public function setDefaultOrderStrategyClass($defaultOrderStrategyClass) {
        $this->defaultOrderStrategyClass = $defaultOrderStrategyClass;
        return $this;
    }

}
