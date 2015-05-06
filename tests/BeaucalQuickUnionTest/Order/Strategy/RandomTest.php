<?php

namespace BeaucalQuickUnionTest\Order\Strategy;

use BeaucalQuickUnion\Order\Strategy;

/**
 * @group beaucal_union
 */
class RandomTest extends \PHPUnit_Framework_TestCase {

    public function testSameOrderMultipleCalls() {
        $random = new Strategy\Random('1', '2');
        $order = $random->getOrder();
        for ($i = 0; $i < 10; $i++) {
            $this->assertSame($order, $random->getOrder());
        }
    }

}
