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

use Ubl\Iiif\Context\JsonLdHelper;
use Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface;

abstract class AbstractDescribableResource1 extends AbstractIiifResource1 {
    /**
     * 
     * @var array
     */
    protected $metadata;
    
    /**
     * 
     * @var string|array
     */
    protected $description;

    /**
     * @return array
     */
    public function getMetadata() {
        return $this->metadata;
    }

    /**
     * @return string|array
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1::getMetadataForDisplay()
     */
    public function getMetadataForDisplay($language = null, $joinChars = "; ", $options = 0) {
        if (!isset($this->metadata) || !JsonLdHelper::isSequentialArray($this->metadata)) {
            return null;
        }
        $result = null;
        foreach ($this->metadata as $metadata) {
            $resultData = [];
            if (array_key_exists("label", $metadata)) {
                $resultData["label"] = $this->getValueForDisplay($metadata["label"], $language, $joinChars, false, IiifResourceInterface::SANITIZE_XML_ENCODE_ALL);
            } else {
                $resultData["label"] = "";
            }
            if (array_key_exists("value", $metadata)) {
                $resultData["value"] = $this->getValueForDisplay($metadata["value"], $language, $joinChars, true, $options);
            } else {
                $resultData["value"] = "";
            }
            $result[] = $resultData;
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1::getSummary()
     */
    public function getSummary() {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V1\Model\Resources\AbstractIiifResource1::getSummaryForDisplay()
     */
    public function getSummaryForDisplay($language = null, $joinChars = "; ", $options = IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML) {
        return $this->getValueForDisplay($this->description, $language, $joinChars);
    }
    
}

