<?php

namespace BeaucalQuickUnionTest\Order;

use BeaucalQuickUnion\Order;

/**
 * @group beaucal_union
 */
class DirectedTest extends \PHPUnit_Framework_TestCase {

    public function testSame() {
        $directed = new Order\Directed('same', 'same');
        $this->assertEquals(['same', 'same'], $directed->getOrder());
    }

    public function testDifferent() {
        $directed = new Order\Directed('diff', 'erent');
        $this->assertEquals(['diff', 'erent'], $directed->getOrder());
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage item is blank
     */
    public function testBlankFirst() {
        new Order\Directed('', 'ok');
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage item is blank
     */
    public function testBlankSecond() {
        new Order\Directed('ok', '');
    }

    /**
     * @expectedException BeaucalQuickUnion\Exception\RuntimeException
     * @expectedExceptionMessage item is blank
     */
    public function testBlankBoth() {
        new Order\Directed('', '');
    }

}
