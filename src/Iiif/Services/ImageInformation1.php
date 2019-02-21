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

class ImageInformation1 extends AbstractImageService {

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
        return "native";
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
                $this->supports = Profile::getSupported($this->profile);
                $formatsByLevel = Profile::getFormats($this->profile);
                if (!empty($formatsByLevel)) {
                    $this->formats = isset($this->formats) ? array_unique(array_merge($this->formats, $formatsByLevel)) : $formatsByLevel;
                }
                $qualitiesByLevel = Profile::getQualities($this->profile);
                if (!empty($qualitiesByLevel)) {
                    $this->qualities = isset($this->qualities) ? array_unique(array_merge($this->qualities, $qualitiesByLevel)) : $qualitiesByLevel;
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
        return null;
    }

}

