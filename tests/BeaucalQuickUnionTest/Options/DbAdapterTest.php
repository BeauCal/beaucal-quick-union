<?php

namespace BeaucalQuickUnionTest\Options;

use BeaucalQuickUnion\Options\DbAdapter as DbAdapterOptions;

/**
 * @group beaucal_union
 */
class DbAdapterTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var DbAdapterOptions
     */
    protected $options;

    public function setUp() {
        parent::setUp();

        $this->options = new DbAdapterOptions;
    }

    public function testDefaults() {
        $defaults = [
            'use_transactions' => true,
            'db_adapter_class' => 'Zend\Db\Adapter\Adapter',
            'db_table' => 'beaucal_union',
            'default_order_strategy_class' => 'BeaucalQuickUnion\Order\Strategy\Directed',
        ];
        foreach ($defaults as $property => $expected) {
            $this->assertEquals($expected, $this->options->{$property});
        }
    }

    public function testSetters() {
        $overrides = [
            'use_transactions' => false,
            'db_adapter_class' => 'another',
            'db_table' => 'another',
            'default_order_strategy_class' => 'another',
        ];
        foreach ($overrides as $property => $override) {
            $this->options->{$property} = $override;
            $this->assertEquals($override, $this->options->{$property});
        }
    }

    public function testConfigOverrides() {
        $config = require __DIR__ . '/data/beaucalquickunion.local.php';
        $options = new DbAdapterOptions($config['beaucalquickunion']['BeaucalQuickUnion\Adapter\Db']);
        $this->assertEquals(
        'use_transactions_another', $options->getUseTransactions()
        );
        $this->assertEquals(
        'db_adapter_class_another', $options->getDbAdapterClass()
        );
        $this->assertEquals('db_table_another', $options->getDbTable());
        $this->assertEquals(
        'default_order_strategy_class_another',
        $options->getDefaultOrderStrategyClass()
        );
    }

}
