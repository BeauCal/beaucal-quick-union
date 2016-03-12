<?php

namespace BeaucalQuickUnion\Factory;

use Zend\Db\Adapter\Adapter as ZendDbAdapter;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Config as ServiceManagerConfig;

/**
 * @group beaucal_union
 */
class FactoryTest extends \PHPUnit_Framework_TestCase {

    protected $serviceManager;

    /**
     * @var ZendDbAdapter
     */
    protected $adapter;
    protected $configMock = ['success'];

    public function setUp() {
        parent::setUp();
        $config = include __DIR__ . '/../../../config/module.config.php';
        $this->serviceManager = new ServiceManager(
        new ServiceManagerConfig($config['service_manager'])
        );

        $this->serviceManager->setFactory('Zend\Db\Adapter\Adapter',
        function($sm) {
            return $this->getAdapter();
        });

        $this->serviceManager->setFactory('Config',
        function($sm) {
            return [
                'beaucalquickunion' => $this->configMock
            ];
        });
    }

    protected function getAdapter() {
        if ($this->adapter) {
            return $this->adapter;
        }
        $config = include __DIR__ . '/../../dbadapter.php';
        $config = $config['db'];
        $config['driver'] = 'PDO';
        $this->adapter = new ZendDbAdapter($config);
        return $this->adapter;
    }

    public function testConfigFactory() {
        $config = $this->serviceManager->get('beaucalquickunion_config');
        $this->assertEquals($this->configMock, $config);
    }

    public function testDbAdapterOptionsFactory() {
        $class = 'BeaucalQuickUnion\Options\DbAdapter';
        $options = $this->serviceManager->get($class);
        $this->assertInstanceOf($class, $options);
    }

    public function testUnionOptionsFactory() {
        $class = 'BeaucalQuickUnion\Options\Union';
        $options = $this->serviceManager->get($class);
        $this->assertInstanceOf($class, $options);
    }

    public function testUnionFactory() {
        $class = 'BeaucalQuickUnion\Service\Union';
        $union = $this->serviceManager->get($class);
        $this->assertInstanceOf($class, $union);

        $this->assertEquals(
        'BeaucalQuickUnion\Adapter\Db', $union->getAdapterClass()
        );
        $this->assertEquals(
        $union->getOptions()->getAdapterClass(), $union->getAdapterClass()
        );

        /**
         * Alias.
         */
        $this->assertSame(
        $union, $this->serviceManager->get('BeaucalQuickUnion')
        );
    }

    public function testDbAdapterFactory() {
        $class = 'BeaucalQuickUnion\Adapter\Db';
        $options = $this->serviceManager->get($class);
        $this->assertInstanceOf($class, $options);
    }

    public function testUnionMemoryFactory() {
        $this->serviceManager->setAllowOverride(true);
        $this->serviceManager->setFactory('Config',
        function($sm) {
            return [
                'beaucalquickunion' => [
                    'union' => [
                        'adapter_class' => 'BeaucalQuickUnion\Adapter\Memory'
                    ]
                ]
            ];
        });
        $union = $this->serviceManager->get('BeaucalQuickUnion');
        $this->assertEquals(
        'BeaucalQuickUnion\Adapter\Memory', $union->getAdapterClass()
        );
        $this->assertEquals(
        $union->getOptions()->getAdapterClass(), $union->getAdapterClass()
        );
    }

}
