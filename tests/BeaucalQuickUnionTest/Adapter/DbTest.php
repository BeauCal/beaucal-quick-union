<?php

namespace BeaucalQuickUnionTest\Adapter;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as DbAdapter;
use BeaucalQuickUnion\Service\Union;
use BeaucalQuickUnion\Adapter\Db as UnionDbAdapter;
use BeaucalQuickUnion\Options\DbAdapter as UnionDbAdapterOptions;
use BeaucalQuickUnion\Options\Union as UnionOptions;

/**
 * @group beaucal_union
 */
class DbTest extends \PHPUnit_Extensions_Database_TestCase {

    /**
     * @var DbAdapter
     */
    protected $dbAdapter;

    /**
     * @var TableGateway
     */
    protected $gateway;

    /**
     * @var UnionDbAdapter
     */
    protected $unionDbAdapter;

    /**
     * @var Union
     */
    protected $union;

    public function setUp() {
        parent::setUp();

        $dbOptions = new UnionDbAdapterOptions;
        $this->gateway = new TableGateway(
        $dbOptions->getDbTable(), $this->getAdapter()
        );
        $this->unionDbAdapter = new UnionDbAdapter($this->gateway, $dbOptions);

        $unionOptions = new UnionOptions;
        $this->union = new Union(
        $this->unionDbAdapter, $unionOptions
        );
    }

    protected function getAdapter() {
        if ($this->dbAdapter) {
            return $this->dbAdapter;
        }
        $config = include __DIR__ . '/../../dbadapter.php';
        $config = $config['db'];
        $config['driver'] = 'PDO';
        $this->dbAdapter = new DbAdapter($config);
        return $this->dbAdapter;
    }

    protected function getConnection() {
        return $this->createDefaultDBConnection($this->getAdapter()->getDriver()->getConnection()->getResource());
    }

    protected function getDataSet() {
        return $this->createFlatXMLDataSet(__DIR__ . '/data/beaucal_union-seed.xml');
    }

}
