<?php
namespace iiif\services;

class PhysicalDimensions extends Service {

    /**
     *
     * @var float
     */
    protected $physicalScale;

    /**
     *
     * @var string
     */
    protected $physicalUnits;

    /**
     *
     * @return number
     */
    public function getPhysicalScale() {
        return $this->physicalScale;
    }

    /**
     *
     * @return string
     */
    public function getPhysicalUnits() {
        return $this->physicalUnits;
    }
}

