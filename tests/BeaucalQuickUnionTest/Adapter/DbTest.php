<?php

namespace BeaucalQuickUnionTest\Adapter;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as DbAdapter;
use BeaucalQuickUnion\Service\Union;
use BeaucalQuickUnion\Adapter\Db as UnionDbAdapter;
use BeaucalQuickUnion\Options\DbAdapter as UnionDbAdapterOptions;
use BeaucalQuickUnion\Options\Union as UnionOptions;
use BeaucalQuickUnion\Order;

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
    protected $unionAdapter;

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
        $this->unionAdapter = new UnionDbAdapter($this->gateway, $dbOptions);

        $unionOptions = new UnionOptions;
        $this->union = new Union(
        $this->unionAdapter, $unionOptions
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
        return $this->createFlatXMLDataSet(__DIR__ . '/../data/beaucal_union-seed.xml');
    }

    public function testUnionBothNew() {
        $this->union->union(new Order\Directed('new1', 'new2'));
        $this->assertCount(1, $this->gateway->select(['item' => 'new1']));
        $this->assertCount(2, $this->gateway->select(['set' => 'new2']));
    }

    public function testUnionItemNew() {
        $this->union->union(new Order\Directed('new1', 'A'));
        $this->assertCount(1, $this->gateway->select(['item' => 'new1']));
        $this->assertCount(2, $this->gateway->select(['set' => 'A']));
    }

    public function testUnionSetNew() {
        $this->union->union(new Order\Directed('A', 'new1'));
        $this->assertCount(1, $this->gateway->select(['item' => 'A']));
        $this->assertCount(2, $this->gateway->select(['set' => 'new1']));
    }

    public function testUnionBothExist1() {
        $this->union->union(new Order\Directed('Y', 'Z'));
        $this->assertCount(1, $this->gateway->select(['item' => 'Y']));
        $this->assertCount(2, $this->gateway->select(['set' => 'Z']));
    }

    public function testUnionBothExist2() {
        $this->union->union(new Order\Directed('A', 'B'));
        $this->assertCount(1, $this->gateway->select(['item' => 'A']));
        $this->assertCount(3, $this->gateway->select(['set' => 'C']));
    }

    public function testUnionFlatten() {
        $this->union->union(new Order\Directed('A', 'B'));
        $this->assertCount(1, $this->gateway->select(['item' => 'A']));
        $this->assertCount(3, $this->gateway->select(['set' => 'C']));
    }

    public function testGetOptions() {
        $this->assertInstanceOf(
        'BeaucalQuickUnion\Options\DbAdapter', $this->unionAdapter->getOptions()
        );
    }

}
