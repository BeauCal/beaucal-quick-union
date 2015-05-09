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
            'db_adapter_class' => 'Zend\Db\Adapter\Adapter',
            'db_table' => 'beaucal_union',
        ];
        foreach ($defaults as $property => $expected) {
            $this->assertEquals($expected, $this->options->{$property});
        }
    }

    public function testSetters() {
        $overrides = [
            'db_adapter_class' => 'another',
            'db_table' => 'another',
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
        'db_adapter_class_another', $options->getDbAdapterClass()
        );
        $this->assertEquals('db_table_another', $options->getDbTable());
    }

}
