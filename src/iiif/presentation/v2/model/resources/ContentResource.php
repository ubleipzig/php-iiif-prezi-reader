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

use iiif\services\AbstractImageService;
use iiif\presentation\v2\model\properties\WidthAndHeightTrait;
use iiif\presentation\common\model\resources\ContentResourceInterface;
use iiif\tools\Options;

class ContentResource extends AbstractIiifResource2 implements ContentResourceInterface {
    use WidthAndHeightTrait;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $chars;

    /**
     *
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     *
     * @return string
     */
    public function getChars() {
        return $this->chars;
    }

    public function getImageUrl() {
        $service = $this->service;
        if ($service instanceof AbstractImageService) {
            return $service->getImageUrl();
        }
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource2::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $services = is_array($this->service) ? $this->service : [$this->service];
        foreach ($services as $service) {
            if ($service instanceof AbstractImageService) {
                $size = $this->width <= $this->height ? ",".Options::getMaxThumbnailHeight() : (Options::getMaxThumbnailWidth().",");
                return $service->getImageUrl(null, $size);
            }
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ContentResourceInterface::isImage()
     */
    public function isImage() {
        return $this->type = "dctypes:Image";
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ContentResourceInterface::isText()
     */
    public function isText() {
        return $this->type == "dctypes:Text" || $this->type == "cnt:ContentAsText";
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(), [
            "http://purl.org/dc/elements/1.1/format" => "format"
        ]);
    }


    
}

