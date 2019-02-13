<?php
/*
 * Copyright (C) 2019 Leipzig University Library <info@ub.uni-leipzig.de>
 * 
 * This file is part of the php-iiif-prezi-reader.
 * 
 * php-iiif-prezi-reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace iiif\presentation\v2\model\resources;

use iiif\presentation\common\model\resources\AnnotationInterface;
use iiif\presentation\v2\model\properties\XYWHFragment;
use iiif\presentation\common\vocabulary\Motivation;

class Annotation extends AbstractIiifResource2 implements AnnotationInterface {

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
    
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        if (Motivation::isPaintingMotivation($this->motivation) && $this->resource!=null) {
            return $this->resource->getThumbnailUrl();
        }
        return null;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\AnnotationInterface::getBody()
     */
    public function getBody() {
        return $this->resource;
    }


}

