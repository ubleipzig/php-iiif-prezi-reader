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

namespace Ubl\Iiif\Presentation\V3\Model\Resources;

use Ubl\Iiif\Presentation\Common\Model\LazyLoadingIterator;
use Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface;
use Ubl\Iiif\Presentation\V3\Model\Properties\PlaceholderAndAccompanyingCanvasTrait;
use Ubl\Iiif\Presentation\Common\Vocabulary\Motivation;

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
     * @return \Ubl\Iiif\Presentation\V3\Model\Resources\Annotation3[]
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
     * @return multitype:\Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationPage3
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationPage3
     */
    public function getAnnotations() {
        return $this->annotations;
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
                // TODO ensure to only use embedded annotations
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
     * @see \Ubl\Iiif\Presentation\V3\Model\Resources\AbstractIiifResource3::getThumbnailUrl()
     */
    public function getThumbnailUrl(): ?string
    {
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers($motivation = null) {
        if ($motivation == null) {
            $result = [];
            if (!empty($this->items)) {
                $result = array_merge($result, $this->items);
            }
            if (!empty($this->annotations)) {
                $result = array_merge($result, $this->annotations);
            }
            return $result;
        } else if ($motivation == Motivation::PAINTING) {
            return $this->items;
        }
        return $this->annotations;
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getPotentialTextAnnotationContainerIterator()
     */
    public function getPotentialTextAnnotationContainerIterator($painting = null): ?LazyLoadingIterator
    {
        // TODO Auto-generated method stub
        
        return null;
    }
    
}

