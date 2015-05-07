<?php

$dbAdapter = [
    'db_adapter_class' => 'db_adapter_class_another',
    'db_table' => 'db_table_another',
    'default_order_strategy_class' => 'default_order_strategy_class_another',
];

$union = [
    'adapter_class' => 'adapter_class_another',
    'loop_damage_control' => 'loop_damage_control_another',
];

return [
    'beaucalquickunion' => [
        'union' => $union,
        'BeaucalQuickUnion\Adapter\Db' => $dbAdapter,
    ],
];
