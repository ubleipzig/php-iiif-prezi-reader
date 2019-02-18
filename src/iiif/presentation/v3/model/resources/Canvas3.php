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

namespace iiif\presentation\v3\model\resources;

use iiif\presentation\common\model\resources\CanvasInterface;
use iiif\presentation\v3\model\properties\PlaceholderAndAccompanyingCanvasTrait;

class Canvas3 extends AbstractIiifResource3 implements CanvasInterface {

    use PlaceholderAndAccompanyingCanvasTrait;
    
    /**
     *
     * @var AnnotationPage3[]
     */
    protected $items;

    /**
     *
     * @var AnnotationPage3[]
     */
    protected $annotations;

    /**
     *
     * @var string
     */
    protected $navDate;

    /**
     *
     * @var int
     */
    protected $height;

    /**
     *
     * @var int
     */
    protected $width;

    /**
     *
     * @var float
     */
    protected $duration;

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Annotation3[]
     */
    public function getImageAnnotationsForDisplay() {
        $imageAnnotations = [];
        foreach ($this->getItems() as $annotationPage) {
            foreach ($annotationPage->getItems() as $annotation) {
                /* @var $annotation Annotation3 */
                if ($annotation->getMotivation() == "painting" && $annotation->getBody()->getType() == "Image") {
                    $imageAnnotations[] = $annotation;
                }
            }
        }
        return $imageAnnotations;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3
     */
    public function getAnnotations() {
        return $this->items;
    }

    /**
     *
     * @return string
     */
    public function getNavDate() {
        return $this->navDate;
    }

    /**
     *
     * @return number
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     *
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     *
     * @return number
     */
    public function getDuration() {
        return $this->duration;
    }
    
    public function getImageAnnotations() {
        $result = [];
        if (isset($this->items)) {
            foreach ($this->items as $annotationPage) {
                // TODO ensure to only use embeded annotations
                if (!empty($annotationPage->getItems())) {
                    foreach ($annotationPage->getItems() as $annotation) {
                        if ($annotation->getMotivation() == "painting" && $annotation->getBody()!=null && $annotation->getBody()->getType() == "Image") {
                            $result[] = $annotation;
                        }
                    }
                }
            }
        }
        return $result;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v3\model\resources\AbstractIiifResource3::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result= parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $images = $this->getImageAnnotationsForDisplay();
        if (!empty($images)) {
            return $images[0]->getThumbnailUrl();
        }
        return null;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers() {
        // TODO Auto-generated method stub
        
    }

}

