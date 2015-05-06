<?php

namespace BeaucalQuickUnion\Factory;

use BeaucalQuickUnion\Options\DbAdapter as DbAdapterOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class DbAdapterOptionsFactory implements FactoryInterface {

    const CONFIG_KEY = 'BeaucalQuickUnion\Adapter\Db';

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('beaucalquickunion_config');
        return new DbAdapterOptions(
        isset($config[self::CONFIG_KEY]) ? $config[self::CONFIG_KEY] : []
        );
    }

}
