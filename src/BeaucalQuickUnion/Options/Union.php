<?php

namespace BeaucalQuickUnion\Options;

use Zend\Stdlib\AbstractOptions;

class Union extends AbstractOptions {

    protected $adapterClass = 'BeaucalQuickUnion\Adapter\Db';
    protected $orderClass = 'BeaucalQuickUnion\Order\Set';
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
     * @return string
     */
    public function getOrderClass() {
        return $this->orderClass;
    }

    /**
     * @param string $orderClass
     * @return Union
     */
    public function setOrderClass($orderClass) {
        $this->orderClass = $orderClass;
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
