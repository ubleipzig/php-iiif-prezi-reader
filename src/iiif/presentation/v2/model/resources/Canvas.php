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

use iiif\presentation\v2\model\properties\WidthAndHeightTrait;
use iiif\presentation\common\model\resources\CanvasInterface;

class Canvas extends AbstractIiifResource2 implements CanvasInterface {
    use WidthAndHeightTrait;

    const TYPE = "sc:Canvas";

    /**
     *
     * @var Annotation[]
     */
    protected $images = array();

    /**
     *
     * @var AnnotationList[]
     */
    protected $otherContent = array();

    /**
     *
     * @return Annotation[]:
     */
    public function getImages() {
        return $this->images;
    }

    /**
     *
     * @return AnnotationList[]:
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
     * @see \iiif\presentation\common\model\resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        if (!empty($this->images)) {
            return $this->images[0]->getThumbnailUrl();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers() {
        return $this->otherContent;
    }

}

