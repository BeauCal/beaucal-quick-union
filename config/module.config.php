<?php

return [
    'service_manager' => [
        'aliases' => [
            'BeaucalQuickUnion' => 'BeaucalQuickUnion\Service\Union',
        ],
        'factories' => [
            'beaucalquickunion_config' => 'BeaucalQuickUnion\Factory\ConfigFactory',
            'beaucalquickunion_memory' => 'BeaucalQuickUnion\Factory\MemoryUnionFactory',
            'BeaucalQuickUnion\Service\Union' => 'BeaucalQuickUnion\Factory\UnionFactory',
            'BeaucalQuickUnion\Options\Union' => 'BeaucalQuickUnion\Factory\UnionOptionsFactory',
            'BeaucalQuickUnion\Adapter\Db' => 'BeaucalQuickUnion\Factory\DbAdapterFactory',
            'BeaucalQuickUnion\Options\DbAdapter' => 'BeaucalQuickUnion\Factory\DbAdapterOptionsFactory',
            'BeaucalQuickUnion\Adapter\Memory' => 'BeaucalQuickUnion\Factory\MemoryAdapterFactory',
            'BeaucalQuickUnion\Options\MemoryAdapter' => 'BeaucalQuickUnion\Factory\MemoryAdapterOptionsFactory',
        ],
    ],
];
