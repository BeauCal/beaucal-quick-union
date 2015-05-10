<?php

namespace BeaucalQuickUnion\Factory;

use BeaucalQuickUnion\Adapter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class MemoryAdapterFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        return new Adapter\Memory(
        $serviceLocator->get('BeaucalQuickUnion\Options\MemoryAdapter')
        );
    }

}
