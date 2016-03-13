<?php

namespace BeaucalQuickUnion\Factory;

use BeaucalQuickUnion\Service\Union;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

/**
 * A shortcut for memory union, rather than modifying config adapter_class.
 */
class MemoryUnionFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $sm) {
        $adapterClass = 'BeaucalQuickUnion\Adapter\Memory';
        $options = clone $sm->get('BeaucalQuickUnion\Options\Union');
        $options->setAdapterClass($adapterClass);
        return new Union(
        $sm->get($options->getAdapterClass()), $options
        );
    }

}
