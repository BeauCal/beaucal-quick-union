<?php

namespace BeaucalQuickUnionTest\Adapter;

use BeaucalQuickUnion\Adapter;
use BeaucalQuickUnion\Options;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as DbAdapter;

/**
 * @group beaucal_union
 */
class AdaptersTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Adapter\AdapterInterface
     */
    protected $adapters;

    /**
     * @var array[string] => string
     */
    protected $tree;

    public function setUp() {
        parent::setUp();

        $this->adapters[] = new Adapter\Memory(new Options\MemoryAdapter);

        $dbOptions = new Options\DbAdapter;
        $dbAdapter = $this->getAdapter();
        $gateway = new TableGateway($dbOptions->getDbTable(), $dbAdapter);
        $dbAdapter->query("truncate {$dbOptions->getDbTable()}")->execute();
        $this->adapters[] = new Adapter\Db($gateway, $dbOptions);

        // single
        $this->insert('A');

        // pair
        $this->insert('C');
        $this->insert('B');
        $this->setParent('B', 'C');

        // chain
        $this->insert('F');
        $this->insert('E');
        $this->insert('D');
        $this->setParent('E', 'F');
        $this->setParent('D', 'E');

        // simplest tree
        $this->insert('H');
        $this->insert('G');
        $this->insert('I');
        $this->setParent('G', 'H');
        $this->setParent('I', 'H');


        // full tree
        $this->insert('P');
        $this->insert('N');
        $this->insert('M');
        $this->insert('O');
        $this->insert('K');
        $this->insert('J');
        $this->insert('L');
        $this->setParent('N', 'P');
        $this->setParent('M', 'N');
        $this->setParent('O', 'N');
        $this->setParent('K', 'P');
        $this->setParent('J', 'K');
        $this->setParent('L', 'K');

        // more singles
        $this->insert('Y');
        $this->insert('Z');
    }

    protected function getAdapter() {
        $config = include __DIR__ . '/../../dbadapter.php';
        $config = $config['db'];
        $config['driver'] = 'PDO';
        return new DbAdapter($config);
    }

    protected function insert($item) {
        foreach ($this->adapters as $adapter) {
            $adapter->insert($item);
        }
    }

    protected function setParent($item1, $item2) {
        foreach ($this->adapters as $adapter) {
            $adapter->setParent($item1, $item2);
        }
    }

    /**
     *
     * @param string $item
     * @return mixed        string if all adapters jibe, null if not
     */
    protected function getParent($item) {
        $sets = [];
        foreach ($this->adapters as $adapter) {
            $sets[] = $adapter->getParent($item);
        }
        $set = array_unique($sets);
        return (count($set) == 1) ? current($set) : null;
    }

    public function testUnionBothNew() {
        $this->insert('new1');
        $this->insert('new2');
        $this->setParent('new1', 'new2');
        $this->assertEquals('new2', $this->getParent('new1'));
        $this->assertEquals('new2', $this->getParent('new2'));
    }

    public function testUnionItemNew() {
        $this->insert('new1');
        $this->setParent('new1', 'A');
        $this->assertEquals('A', $this->getParent('new1'));
    }

    public function testUnionSetNew() {
        $this->insert('new1');
        $this->setParent('A', 'new1');
        $this->assertEquals('new1', $this->getParent('A'));
    }

    public function testUnionBothExist1() {
        $this->setParent('Y', 'Z');
        $this->assertEquals('Z', $this->getParent('Y'));
        $this->assertEquals('Z', $this->getParent('Z'));
    }

    public function testUnionBothExist2() {
        $this->setParent('A', 'B');
        $this->assertEquals('B', $this->getParent('A'));
        $this->assertEquals('C', $this->getParent('B'));
        $this->assertEquals('C', $this->getParent('C'));
    }

}
