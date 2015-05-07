<?php

namespace BeaucalQuickUnion\Options;

use Zend\Stdlib\AbstractOptions;

class Union extends AbstractOptions {

    protected $adapterClass = 'BeaucalQuickUnion\Adapter\Db';
    protected $loopDamageControl = true;

    /**
     * @return string
     */
    public function getAdapterClass() {
        return $this->adapterClass;
    }

    /**
     * @param string $adapterClass
     * @return Union
     */
    public function setAdapterClass($adapterClass) {
        $this->adapterClass = $adapterClass;
        return $this;
    }

    /**
     * @return bool
     */
    public function getLoopDamageControl() {
        return $this->loopDamageControl;
    }

    /**
     * @param bool $loopDamageControl
     * @return Union
     */
    public function setLoopDamageControl($loopDamageControl) {
        $this->loopDamageControl = $loopDamageControl;
        return $this;
    }

}
