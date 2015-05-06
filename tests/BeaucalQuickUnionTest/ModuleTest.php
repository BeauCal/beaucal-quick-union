<?php

namespace BeaucalQuickUnionTest;

use BeaucalQuickUnion\Module;

/**
 * @group beaucal_union
 */
class ModuleTest extends \PHPUnit_Framework_TestCase {

    protected $module;

    public function setUp() {
        parent::setUp();

        $this->module = new Module;
    }

    public function testGetConfig() {
        $config = $this->module->getConfig();
        $this->assertTrue(isset($config['service_manager']));
    }

    public function testGetAutoloaderConfig() {
        $config = $this->module->getAutoloaderConfig();
        $namespace = $config['Zend\Loader\StandardAutoloader']['namespaces'];
        $this->assertEquals('BeaucalQuickUnion', key($namespace));
        $this->assertRegExp('#/BeaucalQuickUnion$#', current($namespace));
    }

}
