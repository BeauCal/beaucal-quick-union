<?php

namespace BeaucalQuickUnion\Factory;

use BeaucalQuickUnion\Adapter\Db as DbAdapter;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\TableGateway\TableGateway;

class DbAdapterFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $options = $serviceLocator->get('BeaucalQuickUnion\Options\DbAdapter');
        $gateway = new TableGateway(
        $options->getDbTable(),
        $serviceLocator->get($options->getDbAdapterClass())
        );
        return new DbAdapter($gateway, $options);
    }

}