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

class MockIiifResource extends AbstractIiifResource2
{
    /**
     * Only for testing AbstractIiifResource2 methods
     * 
     * @param array $jsonAsArray
     * @param array $allResources
     * @return \iiif\presentation\v2\model\resources\MockIiifResource
     */
    public static function fromArray($jsonAsArray, &$allResources = array())
    {
        $instance = self::createInstanceFromArray($jsonAsArray, $allResources);
        $instance->loadPropertiesFromArray($jsonAsArray, $allResources);
        return $instance;
    }
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }
    public function setLabel($label)
    {
        $this->label = $label;
    }
}

