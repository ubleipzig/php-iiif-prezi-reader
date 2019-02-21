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

use Ubl\Iiif\Presentation\V2\Model\Properties\WidthAndHeightTrait;
use Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface;

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
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(), [
            "http://iiif.io/api/presentation/2#hasImageAnnotations" => "images",
            "http://iiif.io/api/presentation/2#hasLists" => "otherContent",
            "http://www.w3.org/2003/12/exif/ns#width" => "width",
            "http://www.w3.org/2003/12/exif/ns#height" => "height"
        ]);
    }

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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getImageAnnotations()
     */
    public function getImageAnnotations() {
        return $this->images;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getThumbnailUrl()
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface::getPossibleTextAnnotationContainers()
     */
    public function getPossibleTextAnnotationContainers() {
        return $this->otherContent;
    }

}

