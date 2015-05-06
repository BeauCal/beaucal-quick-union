<?php

namespace BeaucalQuickUnion\Factory;

use BeaucalQuickUnion\Options\Union as UnionOptions;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class UnionOptionsFactory implements FactoryInterface {

    const CONFIG_KEY = 'union';

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $config = $serviceLocator->get('beaucalquickunion_config');
        return new UnionOptions(
        isset($config[self::CONFIG_KEY]) ? $config[self::CONFIG_KEY] : []
        );
    }

}
