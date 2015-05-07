<?php

namespace BeaucalQuickUnionTest\Options;

use BeaucalQuickUnion\Options\Union as UnionOptions;

/**
 * @group beaucal_union
 */
class UnionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var DbAdapterOptions
     */
    protected $options;

    public function setUp() {
        parent::setUp();

        $this->options = new UnionOptions;
    }

    public function testDefaults() {
        $defaults = [
            'adapter_class' => 'BeaucalQuickUnion\Adapter\Db',
            'loop_damage_control' => true,
        ];
        foreach ($defaults as $property => $expected) {
            $this->assertEquals($expected, $this->options->{$property});
        }
    }

    public function testSetters() {
        $overrides = [
            'adapter_class' => 'another',
            'loop_damage_control' => 'another',
        ];
        foreach ($overrides as $property => $override) {
            $this->options->{$property} = $override;
            $this->assertEquals($override, $this->options->{$property});
        }
    }

    public function testConfigOverrides() {
        $config = require __DIR__ . '/data/beaucalquickunion.local.php';
        $options = new UnionOptions($config['beaucalquickunion']['union']);
        $this->assertEquals('adapter_class_another', $options->getAdapterClass());
        $this->assertEquals(
        'loop_damage_control_another', $options->getLoopDamageControl()
        );
    }

}
