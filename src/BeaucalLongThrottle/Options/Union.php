<?php

namespace BeaucalQuickUnion\Options;

use Zend\Stdlib\AbstractOptions;

class Union extends AbstractOptions {

    protected $adapterClass = 'BeaucalQuickUnion\Adapter\Db';

    /**
     * @return string
     */
    public function getAdapterClass() {
        return $this->adapterClass;
    }

    /**
     * @param string $adapterClass
     * @return Throttle
     */
    public function setAdapterClass($adapterClass) {
        $this->adapterClass = $adapterClass;
        return $this;
    }

}
