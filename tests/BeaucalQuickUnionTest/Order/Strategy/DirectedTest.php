<?php

namespace BeaucalQuickUnionTest\Order\Strategy;

use BeaucalQuickUnion\Order\Strategy;

/**
 * @group beaucal_union
 */
class DirectedTest extends \PHPUnit_Framework_TestCase {

    public function testSame() {
        $directed = new Strategy\Directed('same', 'same');
        $this->assertEquals(['same', 'same'], $directed->getOrder());
    }

    public function testDifferent() {
        $directed = new Strategy\Directed('diff', 'erent');
        $this->assertEquals(['diff', 'erent'], $directed->getOrder());
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage item is blank
     */
    public function testBlankFirst() {
        new Strategy\Directed('', 'ok');
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage item is blank
     */
    public function testBlankSecond() {
        new Strategy\Directed('ok', '');
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage item is blank
     */
    public function testBlankBoth() {
        new Strategy\Directed('', '');
    }

}
