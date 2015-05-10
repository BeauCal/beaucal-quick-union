<?php

namespace BeaucalQuickUnionTest\Order;

use BeaucalQuickUnion\Order;

/**
 * @group beaucal_union
 */
class SetTest extends \PHPUnit_Framework_TestCase {

    public function testSameOrderMultipleCalls() {
        $random = new Order\Set('1', '2');
        $order = $random->getOrder();
        for ($i = 0; $i < 10; $i++) {
            $this->assertSame($order, $random->getOrder());
        }
    }

}
