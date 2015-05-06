<?php

return [
    'service_manager' => [
        'aliases' => [
            'BeaucalQuickUnion' => 'BeaucalQuickUnion\Service\Union',
        ],
        'factories' => [
            'beaucalquickunion_config' => 'BeaucalQuickUnion\Factory\ConfigFactory',
            'BeaucalQuickUnion\Service\Union' => 'BeaucalQuickUnion\Factory\UnionFactory',
            'BeaucalQuickUnion\Options\Union' => 'BeaucalQuickUnion\Factory\UnionOptionsFactory',
            'BeaucalQuickUnion\Adapter\Db' => 'BeaucalQuickUnion\Factory\DbAdapterFactory',
            'BeaucalQuickUnion\Options\DbAdapter' => 'BeaucalQuickUnion\Factory\DbAdapterOptionsFactory',
        ],
    ],
];
