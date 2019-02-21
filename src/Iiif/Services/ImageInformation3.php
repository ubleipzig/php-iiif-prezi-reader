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

namespace Ubl\Iiif\Services;

class ImageInformation3 extends AbstractImageService {

    protected $extraFeatures = [];

    protected $extraFormats = [];
    
    protected $extraQualities = [];
    
    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::getDefaultFormat()
     */
    protected function getDefaultFormat() {
        return "jpg";
    }

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::getDefaultQuality()
     */
    protected function getDefaultQuality() {
        return "default";
    }

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::getFullRegion()
     */
    protected function getFullRegion() {
        return "full";
    }

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::getMaxSize()
     */
    protected function getMaxSize() {
        return "max";
    }

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::getNoRotation()
     */
    protected function getNoRotation() {
        return "0";
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::initializeProfile()
     */
    protected function initializeProfile() {
        if (!$this->profileInitialized) {
            if (isset($this->extraFeatures) && !empty($this->extraFeatures)) {
                $this->supports = $this->extraFeatures;
            }
            if (isset($this->extraFormats) && !empty($this->extraFormats)) {
                $this->formats = $this->extraFormats;
            }
            if (isset($this->extraQualities) && !empty($this->extraQualities)) {
                $this->qualities = $this->extraQualities;
            }
            if (isset($this->profile) && is_string($this->profile)) {
                $complianceProfile = Profile::getComplianceLevelProfile($this->profile);
                if (isset($complianceProfile)) {
                    $this->formats = array_unique(array_merge($this->formats, $complianceProfile["formats"]));
                    $this->qualities = array_unique(array_merge($this->qualities, $complianceProfile["qualities"]));
                    $this->supports = array_unique(array_merge($this->supports, $complianceProfile["supported"]));
                }
            }
            $this->profileInitialized = true;
        }
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Services\AbstractImageService::getSizes()
     */
    public function getSizes() {
        return $this->sizes;
    }
    
}

