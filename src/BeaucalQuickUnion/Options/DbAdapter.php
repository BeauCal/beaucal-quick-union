<?php

namespace BeaucalQuickUnion\Options;

use Zend\Stdlib\AbstractOptions;

class DbAdapter extends AbstractOptions {

    protected $dbAdapterClass = 'Zend\Db\Adapter\Adapter';
    protected $dbTable = 'beaucal_union';

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

}
