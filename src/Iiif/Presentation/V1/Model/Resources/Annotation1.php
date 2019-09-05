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

namespace Ubl\Iiif\Presentation\V1\Model\Resources;


use Ubl\Iiif\Presentation\Common\Model\Resources\AnnotationInterface;
use Ubl\Iiif\Presentation\Common\Model\XYWHFragment;

class Annotation1 extends AbstractIiifResource1 implements AnnotationInterface {

    /**
     * 
     * @var string
     */
    protected $motivation;

    /**
     * 
     * @var ContentResource1
     */
    protected $resource;

    /**
     * 
     * @var mixed
     */
    protected $on;

    protected function getSpecialTreatmentValue($property, $value, $context) {
        if ($property == "on") {
            $dummyArray = [];
            $possibleXYWHFragment = XYWHFragment::getFromURI($value, $dummyArray, Canvas1::class);
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
     * @return string
     */
    public function getMotivation() {
        return $this->motivation;
    }

    /**
     * @return \Ubl\Iiif\Presentation\V1\Model\Resources\ContentResource1
     */
    public function getResource() {
        return $this->resource;
    }

    /**
     * @return mixed
     */
    public function getOn() {
        return $this->on;
    }

    public function getOnSelector() {
        if ($this->on instanceof XYWHFragment) {
            return $this->on;
        }
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        // TODO
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
            return null;
        }
        if ($this->on instanceof XYWHFragment && $this->on->getTargetObject() != null) {
            return $this->on->getTargetObject()->getId();
        } elseif ($this->on instanceof AbstractIiifResource1) {
            return $this->on->getId();
        }
    }

}

