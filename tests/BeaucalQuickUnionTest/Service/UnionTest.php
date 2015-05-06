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
        return str_shuffle(date('r'));
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

    public function testNew() {
        $this->assertEquals(
        $this->itemNew, $this->union->query($this->itemNew)
        );
    }

    public function testSingle() {
        $root = '32489376-607f-4cbe-b6c1-028d4f2f7d09';
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->uuidNew));
    }

    public function testPair() {
        $child = 'eceeb0aa-81a7-45a9-b79b-5dcd9be3f93a';
        $root = 'a8c7bb41-0253-4c31-b83b-2fb6b9051ed5';
        $this->assertEquals($root, $this->union->query($child));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->uuidNew));
    }

    public function testChain() {
        $child = '3d2213cc-b962-48e4-8024-6e443696dd08';
        $parent = '6757ce41-567e-4179-8799-e3626e35ce0f';
        $root = 'ed840555-96b4-4e9f-9cb1-675ac16aa720';
        $this->assertEquals($root, $this->union->query($child));
        $this->assertEquals($root, $this->union->query($parent));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->uuidNew));
    }

    public function testSimpleTree() {
        $child1 = 'f77e3765-6f7c-4ce2-9936-f46aab7623a1';
        $child2 = 'a01d6676-dbc3-469b-a0c7-4ca9906d90cf';
        $root = '843fbd4d-c343-477a-b933-c9dbeda2aead';
        $this->assertEquals($root, $this->union->query($child1));
        $this->assertEquals($root, $this->union->query($child2));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->uuidNew));
    }

    public function testFullTree() {
        $childAA = 'f74097e2-3441-4be8-a133-a78fed262329';
        $childAB = '6d6cd371-1ea5-4008-8881-335fe5d7c858';
        $childBA = 'b0f917cf-cfa4-41fb-8114-0dd1f6c2443d';
        $childBB = 'cfcd32a2-6225-40b6-9cd9-447c1dcfa8f7';
        $childA = 'a78a9267-94fa-4549-aead-14b402cfd863';
        $childB = '9a30799b-d319-4807-acfb-d1922ea7f35c';
        $root = '45634413-9586-4ffa-af25-3782a79ed2a3';
        $this->assertEquals($root, $this->union->query($childAA));
        $this->assertEquals($root, $this->union->query($childAB));
        $this->assertEquals($root, $this->union->query($childBA));
        $this->assertEquals($root, $this->union->query($childBB));
        $this->assertEquals($root, $this->union->query($childA));
        $this->assertEquals($root, $this->union->query($childB));
        $this->assertEquals($root, $this->union->query($root));
        $this->assertNotEquals($root, $this->union->query($this->uuidNew));
    }

    public function testSeparate() {
        $uuid1 = 'ca9ab71c-c9fd-418f-8f59-e392cd62a1ab';
        $uuid2 = '06d631c5-55fd-433f-9ba0-c6e0fc156dd4';
        $this->assertEquals($uuid1, $this->union->query($uuid1));
        $this->assertEquals($uuid2, $this->union->query($uuid2));
        $this->assertNotEquals($uuid1, $this->union->query($this->uuidNew));
        $this->assertNotEquals($uuid2, $this->union->query($this->uuidNew));
    }

    public function testUnionTwoTrees() {

        /**
         * Trees joined with one random link.
         */
        $uuids1 = [
            'f77e3765-6f7c-4ce2-9936-f46aab7623a1',
            'a01d6676-dbc3-469b-a0c7-4ca9906d90cf',
            '843fbd4d-c343-477a-b933-c9dbeda2aead',
        ];
        $uuids2 = [
            'f74097e2-3441-4be8-a133-a78fed262329',
            '6d6cd371-1ea5-4008-8881-335fe5d7c858',
            'b0f917cf-cfa4-41fb-8114-0dd1f6c2443d',
            'cfcd32a2-6225-40b6-9cd9-447c1dcfa8f7',
            'a78a9267-94fa-4549-aead-14b402cfd863',
            '9a30799b-d319-4807-acfb-d1922ea7f35c',
            '45634413-9586-4ffa-af25-3782a79ed2a3',
        ];
        $this->union->union(new Strategy\Random(
        $uuids1[mt_rand(0, count($uuids1) - 1)],
        $uuids2[mt_rand(0, count($uuids2) - 1)]
        ));

        $results = [];
        foreach (array_merge($uuids1, $uuids2) as $uuid) {
            $results[] = $this->union->query($uuid);
        }
        $this->assertCount(1, array_unique($results));
    }

    /**
     * Smoke out infinite recursion.
     */
    public function testMonteCarloUnion() {
        $uuids = [];
        for ($i = 0; $i < 10; $i++) {
            $uuid1 = self::getRandomString();
            $uuid2 = self::getRandomString();
            array_push($uuids, $uuid1, $uuid2);
            $this->union->union(
            new Strategy\Random($uuid1, $uuid2)
            );
        }
        for ($i = 0; $i < 20; $i++) {
            $uuid1 = $uuids[mt_rand(0, count($uuids) - 1)];
            $uuid2 = $uuids[mt_rand(0, count($uuids) - 1)];
            $this->union->union(
            new Strategy\Random($uuid1, $uuid2)
            );
        }
        for ($i = 0; $i < 20; $i++) {
            $uuid = $uuids[mt_rand(0, count($uuids) - 1)];
            $this->union->query($uuid);
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
        $uuids = [self::getRandomString(), self::getRandomString()];
        $order = new Strategy\Directed($uuids[0], $uuids[1]);
        $this->assertEquals($uuids, $order->getOrder());
        $this->assertNotEquals($uuids, array_reverse($order->getOrder()));
    }

}
