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

use Ubl\Iiif\Context\JsonLdHelper;

class ImageInformation2 extends AbstractImageService {

    protected $attribution;
    
    protected $license;
    
    protected $logo;
    
    protected $protocol;
    
    protected $maxWidth = null;
    
    protected $maxHeight = null;
    
    protected $maxArea = null;
    
    protected function getCollectionProperties() {
        return ["profile"];
    }
    
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
        $this->initializeProfile();
        /*
         * Although the API offers "max" as the maximum size, we check if "max" is different from "full".
         * If "full" is not supported because there are limitations on the maximum dimensions or area,
         * we use "max". Otherwise, "full" and "max" are the same and we use "full".
         * The reason behind this is a bug in the widely used iipsrv image server that has only been fixed
         * in December 2017 and is still present in older IIP installations:
         * https://github.com/ruven/iipsrv/commit/916f400c8be9872ff7afec569ad2601eb01a192e#diff-2e48c0738b9a10e853164eacecb8c6ca
         */
        if (isset($this->maxArea) || isset($this->maxWidth) || isset($this->maxHeight)) {
            return "max";
        }
        return "full";
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
            if (isset($this->profile)) {
                $this->profile = JsonLdHelper::isSequentialArray($this->profile) ? $this->profile : [$this->profile];
                foreach ($this->profile as $profileEntry) {
                    if (is_string($profileEntry)) {
                        $complianceProfile = Profile::getComplianceLevelProfile($profileEntry);
                        if (isset($complianceProfile)) {
                            $this->formats = array_unique(array_merge($this->formats, $complianceProfile["formats"]));
                            $this->qualities = array_unique(array_merge($this->qualities, $complianceProfile["qualities"]));
                            $this->supports = array_unique(array_merge($this->supports, $complianceProfile["supported"]));
                        }
                    } elseif (JsonLdHelper::isDictionary($profileEntry)) {
                        foreach ($profileEntry as $key => $value) {
                            switch ($key) {
                                case "formats":
                                    if ($value!=null) {
                                        $this->formats = array_unique(array_merge($this->formats, $value));
                                    }
                                    break;
                                case "qualities":
                                    if ($value!=null) {
                                        $this->qualities = array_unique(array_merge($this->qualities, $value));
                                    }
                                    break;
                                case "supports":
                                    if ($value!=null) {
                                        $this->supports = array_unique(array_merge($this->supports, $value));
                                    }
                                    break;
                                case "maxWidth":
                                case "maxHeight":
                                case "maxMaxArea":
                                    $this->$key = $value;
                                    break;
                            }
                        }
                    }
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

