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

use Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface;
use Ubl\Iiif\Services\AbstractImageService;
use Ubl\Iiif\Tools\Options;

class ContentResource1 extends AbstractIiifResource1 implements ContentResourceInterface {
    
    /**
     * @var int
     */
    protected $width;
    
    /**
     * @var int
     */
    protected $height;
    
    /**
     * @var string
     */
    protected $format;

    /**
     * @var string;
     */
    protected $chars;
    /**
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return number
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @return string;
     */
    public function getChars() {
        return $this->chars;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1::getThumbnailUrl()
     */
    public function getThumbnailUrl(): ?string
    {
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface::isImage()
     */
    public function isImage() {
        return $this->type == "dctypes:Image";
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface::isText()
     */
    public function isText() {
        return $this->type == "dctypes:Text" || $this->type == "cnt:ContentAsText";
    }
    
}

