<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\XYWHFragment;

class Annotation extends AbstractIiifResource {

    const TYPE = "oa:Annotation";

    protected $motivation;

    /**
     *
     * @var ContentResource
     */
    protected $resource;

    protected $on;

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getSpecialTreatmentValue()
     */
    protected function getSpecialTreatmentValue($property, $value, $context) {
        if ($property == "on") {
            $dummyArray = [];
            $possibleXYWHFragment = XYWHFragment::getFromURI($value, $dummyArray, Canvas::class);
            if ($possibleXYWHFragment != null) {
                if (strpos($possibleXYWHFragment->getFragment(), "xywh")===0) {
                    return $possibleXYWHFragment;
                } else {
                    return $possibleXYWHFragment->getTargetObject();
                }
            }
        }
        return parent::getSpecialTreatmentValue($property, $value, $context);
    }

    /**
     *
     * @return \iiif\presentation\v2\model\resources\ContentResource
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     *
     * @return XYWHFragment|Canvas
     */
    public function getOn() {
        return $this->on;
    }
    
    /**
     *
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }
}

