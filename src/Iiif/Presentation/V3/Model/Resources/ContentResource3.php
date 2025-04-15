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

use Ubl\Iiif\Services\AbstractImageService;
use Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface;
use Ubl\Iiif\Tools\Options;

class ContentResource3 extends AbstractIiifResource3 implements ContentResourceInterface {

    /**
     *
     * @var AnnotationPage3[]
     */
    protected $annotations;

    /**
     *
     * @var string
     */
    protected $language;

    /**
     *
     * @var string
     */
    protected $format;

    /**
     *
     * @var string
     */
    protected $profile;

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
     * @var string
     */
    protected $value;
    

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
    public function getLanguage() {
        return $this->language;
    }

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
    public function getProfile() {
        return $this->profile;
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

    public function getImageUrl($width = null, $height = null) {
        if (($services = $this->service) != null) {
            if (is_array($services)) {
                foreach ($services as $service) {
                    if ($service instanceof AbstractImageService) {
                        break;
                    }
                }
            } else {
                $service = $services;
            }
            if ($service instanceof AbstractImageService) {
                $size = "full";
                if ($width != null && $height != null) {
                    $size = $width . "," . $height;
                } elseif ($width != null) {
                    $size = $width . ",";
                } elseif ($height != null) {
                    $size = "," . $height;
                }
                return $service->getImageUrl(null, $size);
            }
        }
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V3\Model\Resources\AbstractIiifResource3::getThumbnailUrl()
     */
    public function getThumbnailUrl(): ?string
    {
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface::getChars()
     */
    public function getChars() {
        return $this->value;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface::isImage()
     */
    public function isImage() {
        return $this->type == "Image";
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ContentResourceInterface::isText()
     */
    public function isText() {
        return $this->type == "Text";
    }
    
}

