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

use Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface;

class Canvas1 extends AbstractDescribableResource1 implements CanvasInterface {

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
     * @var Annotation1[]
     */
    protected $images;

    /**
     *
     * @var Annotation1[]
     */
    protected $otherContent;
    /**
     * @return number
     */

    public function getHeight() {
        return $this->height;
    }

    /**
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return multitype:\Ubl\Iiif\Presentation\V1\Model\Resources\Annotation1 
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * @return multitype:\Ubl\Iiif\Presentation\V1\Model\Resources\Annotation1 
     */
    public function getOtherContent() {
        return $this->otherContent;
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getImageAnnotations()
     */
    public function getImageAnnotations() {
        return $this->images;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers($motivation = null) {
        return $this->otherContent;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getPotentialTextAnnotationContainerIterator()
     */
    public function getPotentialTextAnnotationContainerIterator($painting = null) {
        // TODO Auto-generated method stub
        
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        if (!empty($this->images)) {
            return $this->images[0]->getThumbnailUrl();
        }
        return null;
    }
    
}