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

namespace Ubl\Iiif\Presentation\V2\Model\Resources;

use Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationInterface;
use Ubl\Iiif\Presentation\V2\Model\Properties\XYWHFragment;
use Ubl\Iiif\Presentation\Common\Vocabulary\Motivation;

class Annotation2 extends AbstractIiifResource2 implements AnnotationInterface {

    const TYPE = "oa:Annotation";

    protected $motivation;

    /**
     *
     * @var ContentResource2
     */
    protected $resource;

    protected $on;

    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(), [
            "http://www.w3.org/ns/oa#motivatedBy" => "motivation",
            "http://www.w3.org/ns/oa#hasBody" => "resource",
            "http://www.w3.org/ns/oa#hasTarget" => "on"
        ]);
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity::getSpecialTreatmentValue()
     */
    protected function getSpecialTreatmentValue($property, $value, $context) {
        if ($property == "on") {
            $dummyArray = [];
            $possibleXYWHFragment = XYWHFragment::getFromURI($value, $dummyArray, Canvas2::class);
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
     * @return \Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource2
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     *
     * @return XYWHFragment|Canvas2
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationInterface::getBody()
     */
    public function getBody() {
        return $this->resource;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationInterface::getTargetResourceId()
     */
    public function getTargetResourceId() {
        if ($this->on == null) {
            // this should not happen with valid annotations
            return null;
        }
        if ($this->on instanceof XYWHFragment && $this->on->getTargetObject() != null) {
            return $this->on->getTargetObject()->getId();
        } elseif ($this->on instanceof AbstractIiifResource2) {
            return $this->on->getId();
        }
    }

}

