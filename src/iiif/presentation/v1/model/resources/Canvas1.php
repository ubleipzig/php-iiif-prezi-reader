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

namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\resources\CanvasInterface;

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
     * @return multitype:\iiif\presentation\v1\model\resources\Annotation1 
     */
    public function getImages() {
        return $this->images;
    }

    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Annotation1 
     */
    public function getOtherContent() {
        return $this->otherContent;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CanvasInterface::getImageAnnotations()
     */
    public function getImageAnnotations() {
        return $this->images;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers() {
        return $this->otherContent;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getThumbnailUrl()
     */

    public function getThumbnailUrl() {
        if (!empty($this->images)) {
            return $this->images[0]->getThumbnailUrl();
        }
        return null;
    }
    
}