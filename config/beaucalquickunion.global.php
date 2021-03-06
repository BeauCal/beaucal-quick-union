<?php

/**
 * If overriding anything, drop into ./config/autoload/
 */
$dbAdapter = [
//    'db_adapter_class' => 'Zend\Db\Adapter\Adapter',
//    'db_table' => 'beaucal_union',
];
$memoryAdapter = [
//    no options
];

$union = [
//    'adapter_class' => 'BeaucalQuickUnion\Adapter\Db',
//
//    Order\Set: order doesn't matter
//    Order\Directed: order as given
//    'order_class' => 'BeaucalQuickUnion\Order\Set',
//
//    When a node is found to be its own grandparent:
//    TRUE: just break the loop and carry on
//    FALSE: throw LoopException
//    'loop_damage_control' => true
];

return [
    'beaucalquickunion' => [
        'union' => $union,
        'BeaucalQuickUnion\Adapter\Db' => $dbAdapter,
        'BeaucalQuickUnion\Adapter\Memory' => $memoryAdapter,
    ],
];
