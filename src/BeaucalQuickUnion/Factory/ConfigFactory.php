<?php

namespace BeaucalQuickUnion\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class ConfigFactory implements FactoryInterface {

    const CONFIG_KEY = 'beaucalquickunion';

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('Config');
        return isset($config[self::CONFIG_KEY]) ? $config[self::CONFIG_KEY] : [];
    }

}
