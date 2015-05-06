<?php

namespace BeaucalQuickUnion\Factory;

use BeaucalQuickUnion\Service\Union;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class ThrottleFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $options = $serviceLocator->get('BeaucalQuickUnion\Options\Union');
        return new Union(
        $serviceLocator->get($options->getAdapterClass()), $options
        );
    }

}
