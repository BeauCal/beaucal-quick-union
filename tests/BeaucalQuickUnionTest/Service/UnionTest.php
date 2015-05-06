<?php

namespace BeaucalQuickUnion\Service;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as DbAdapter;
use BeaucalQuickUnion\Service\Union;
use BeaucalQuickUnion\Adapter\Db as UnionDbAdapter;
use BeaucalQuickUnion\Options\DbAdapter as UnionDbAdapterOptions;
use BeaucalQuickUnion\Options\Union as UnionOptions;
use BeaucalQuickUnion\Order\Strategy;

/**
 * @group beaucal_union
 */
class UnionTest extends \PHPUnit_Extensions_Database_TestCase {

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
    protected $itemNew;

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

        $this->itemNew = self::getRandomString();
    }

    protected static function getRandomString() {
        return mt_rand(100000000, 999999999);
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

    public function testNew() {
        $this->assertEquals(
        $this->itemNew, $this->union->query($this->itemNew)
        );
    }

    public function testSingle() {
        $root = 'AAAAA';
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->itemNew));
    }

    public function testPair() {
        $child = 'BBBBB';
        $root = 'CCCCC';
        $this->assertEquals($root, $this->union->query($child));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->itemNew));
    }

    public function testChain() {
        $child = 'DDDDD';
        $parent = 'EEEEE';
        $root = 'FFFFF';
        $this->assertEquals($root, $this->union->query($child));
        $this->assertEquals($root, $this->union->query($parent));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->itemNew));
    }

    public function testSimpleTree() {
        $child1 = 'GGGGG';
        $child2 = 'IIIII';
        $root = 'HHHHH';
        $this->assertEquals($root, $this->union->query($child1));
        $this->assertEquals($root, $this->union->query($child2));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->itemNew));
    }

    public function testFullTree() {
        $childAA = 'JJJJJ';
        $childAB = 'LLLLL';
        $childBA = 'MMMMM';
        $childBB = 'OOOOO';
        $childA = 'KKKKK';
        $childB = 'NNNNN';
        $root = 'PPPPP';
        $this->assertEquals($root, $this->union->query($childAA));
        $this->assertEquals($root, $this->union->query($childAB));
        $this->assertEquals($root, $this->union->query($childBA));
        $this->assertEquals($root, $this->union->query($childBB));
        $this->assertEquals($root, $this->union->query($childA));
        $this->assertEquals($root, $this->union->query($childB));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->itemNew));
    }

    public function testSeparate() {
        $item1 = 'YYYYY';
        $item2 = 'ZZZZZ';
        $this->assertEquals($item1, $this->union->query($item1));
        $this->assertEquals($item2, $this->union->query($item2));
        $this->assertNotEquals($item1, $this->union->query($this->itemNew));
        $this->assertNotEquals($item2, $this->union->query($this->itemNew));
    }

    public function testUnionTwoTrees() {

        /**
         * Trees joined with one random link.
         */
        $items1 = [
            'GGGGG',
            'IIIII',
            'HHHHH',
        ];
        $items2 = [
            'JJJJJ',
            'LLLLL',
            'MMMMM',
            'OOOOO',
            'KKKKK',
            'NNNNN',
            'PPPPP',
        ];
        $this->union->union(new Strategy\Random(
        $items1[mt_rand(0, count($items1) - 1)],
        $items2[mt_rand(0, count($items2) - 1)]
        ));

        $results = [];
        foreach (array_merge($items1, $items2) as $item) {
            $results[] = $this->union->query($item);
        }
        $this->assertCount(1, array_unique($results));
    }

    /**
     * Smoke out infinite recursion.
     */
    public function testMonteCarloUnion() {
        $items = [];
        for ($i = 0; $i < 10; $i++) {
            $item1 = self::getRandomString();
            $item2 = self::getRandomString();
            array_push($items, $item1, $item2);
            $this->union->union(
            new Strategy\Random($item1, $item2)
            );
        }
        for ($i = 0; $i < 20; $i++) {
            $item1 = $items[mt_rand(0, count($items) - 1)];
            $item2 = $items[mt_rand(0, count($items) - 1)];
            $this->union->union(
            new Strategy\Random($item1, $item2)
            );
        }
        for ($i = 0; $i < 20; $i++) {
            $item = $items[mt_rand(0, count($items) - 1)];
            $this->union->query($item);
        }
    }

    public function testStrategyRandom() {

        /**
         * Preserve ordering through multiple calls.
         */
        $order = new Strategy\Random(
        self::getRandomString(), self::getRandomString()
        );
        for ($i = 0; $i < 6; $i++) {
            $this->assertEquals($order->getOrder(), $order->getOrder());
            $this->assertNotEquals($order->getOrder(),
            array_reverse($order->getOrder()));
        }
    }

    public function testStrategyDirected() {
        $items = [self::getRandomString(), self::getRandomString()];
        $order = new Strategy\Directed($items[0], $items[1]);
        $this->assertEquals($items, $order->getOrder());
        $this->assertNotEquals($items, array_reverse($order->getOrder()));
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage query must be given a value
     */
    public function testQueryBlank() {
        $this->union->query('');
    }

}
