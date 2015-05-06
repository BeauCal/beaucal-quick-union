<?php

/**
 * If overriding anything, drop into ./config/autoload/
 */
$dbAdapter = [
//    'db_adapter_class' => 'Zend\Db\Adapter\Adapter',
//    'db_table' => 'beaucal_union',
//    'default_order_strategy_class' => 'BeaucalQuickUnion\Order\Strategy\Directed'
];

return [
    'beaucalquickunion' => [
        'union' => [
//            'adapter_class' => 'BeaucalQuickUnion\Adapter\Db',
        ],
        'BeaucalQuickUnion\Adapter\Db' => $dbAdapter,
    ],
];
