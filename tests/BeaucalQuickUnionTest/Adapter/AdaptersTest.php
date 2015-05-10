<?php

namespace BeaucalQuickUnionTest\Adapter;

use BeaucalQuickUnion\Adapter;
use BeaucalQuickUnion\Options;
use BeaucalQuickUnion\Order\Strategy;
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
        $this->union(new Strategy\Directed('AAAAA', 'AAAAA'));

        // pair
        $this->union(new Strategy\Directed('CCCCC', 'CCCCC'));
        $this->union(new Strategy\Directed('BBBBB', 'CCCCC'));

        // chain
        $this->union(new Strategy\Directed('FFFFF', 'FFFFF'));
        $this->union(new Strategy\Directed('EEEEE', 'FFFFF'));
        $this->union(new Strategy\Directed('DDDDD', 'EEEEE'));

        // simplest tree
        $this->union(new Strategy\Directed('HHHHH', 'HHHHH'));
        $this->union(new Strategy\Directed('GGGGG', 'HHHHH'));
        $this->union(new Strategy\Directed('IIIII', 'HHHHH'));


        // full tree
        $this->union(new Strategy\Directed('PPPPP', 'PPPPP'));
        $this->union(new Strategy\Directed('NNNNN', 'PPPPP'));
        $this->union(new Strategy\Directed('MMMMM', 'NNNNN'));
        $this->union(new Strategy\Directed('OOOOO', 'NNNNN'));
        $this->union(new Strategy\Directed('KKKKK', 'PPPPP'));
        $this->union(new Strategy\Directed('JJJJJ', 'KKKKK'));
        $this->union(new Strategy\Directed('LLLLL', 'KKKKK'));

        // more singles
        $this->union(new Strategy\Directed('YYYYY', 'YYYYY'));
        $this->union(new Strategy\Directed('ZZZZZ', 'ZZZZZ'));
    }

    protected function getAdapter() {
        $config = include __DIR__ . '/../../dbadapter.php';
        $config = $config['db'];
        $config['driver'] = 'PDO';
        return new DbAdapter($config);
    }

    protected function union(Strategy\Directed $order) {
        foreach ($this->adapters as $adapter) {
            $adapter->union($order);
        }
    }

    /**
     *
     * @param string $item
     * @return mixed        string if all adapters jibe, null if not
     */
    protected function getSet($item) {
        $sets = [];
        foreach ($this->adapters as $adapter) {
            $sets[] = $adapter->getSet($item);
        }
        $set = array_unique($sets);
        return (count($set) == 1) ? current($set) : null;
    }

    public function testUnionBothNew() {
        $this->union(new Strategy\Directed('new2', 'new2'));
        $this->union(new Strategy\Directed('new1', 'new2'));
        $this->assertEquals('new2', $this->getSet('new1'));
        $this->assertEquals('new2', $this->getSet('new2'));
    }

    public function testUnionItemNew() {
        $this->union(new Strategy\Directed('new1', 'AAAAA'));
        $this->assertEquals('AAAAA', $this->getSet('new1'));
    }

    public function testUnionSetNew() {
        $this->union(new Strategy\Directed('new1', 'new1'));
        $this->union(new Strategy\Flatten('AAAAA', 'new1'));
        $this->assertEquals('new1', $this->getSet('AAAAA'));
    }

    public function testUnionBothExist1() {
        $this->union(new Strategy\Flatten('YYYYY', 'ZZZZZ'));
        $this->assertEquals('ZZZZZ', $this->getSet('YYYYY'));
        $this->assertEquals('ZZZZZ', $this->getSet('ZZZZZ'));
    }

    public function testUnionBothExist2() {
        $this->union(new Strategy\Flatten('AAAAA', 'BBBBB'));
        $this->assertEquals('BBBBB', $this->getSet('AAAAA'));
        $this->assertEquals('CCCCC', $this->getSet('BBBBB'));
        $this->assertEquals('CCCCC', $this->getSet('CCCCC'));
    }

}
