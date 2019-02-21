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

namespace iiif\presentation\common\model\resources;

use iiif\presentation\common\model\AbstractIiifEntity;
use iiif\context\JsonLdHelper;

abstract class AbstractIiifResource extends AbstractIiifEntity implements IiifResourceInterface {
    
    protected function getWeblinksForDisplayCommon($value, $language, $joinChars, $idOrAlias) {
        if (empty($value)) {
            return null;
        }
        if (is_string($value)) {
            return [["@id" => $value]];
        }
        else if ($value instanceof IiifResourceInterface) {
            $value = [$value];
        }
        if (is_array($value)) {
            if (JsonLdHelper::isDictionary($value)) {
                $value = [$value];
            }
            $result = [];
            foreach ($value as $entry) {
                if (is_string($entry)) {
                    $result[]["@id"] = $entry;
                } elseif (JsonLdHelper::isDictionary($entry) && array_key_exists($idOrAlias, $entry)) {
                    $resultEntry = [];
                    $resultEntry["@id"] = $entry[$idOrAlias];
                    if (array_key_exists("label", $entry)) {
                        $resultEntry["label"] = $this->getValueForDisplay($entry["label"], $language, $joinChars);
                    }
                    if (array_key_exists("format", $entry)) {
                        $resultEntry["format"] = $entry["format"];
                    }
                    $result[] = $resultEntry;
                } elseif ($entry instanceof IiifResourceInterface) {
                    $resultEntry = [];
                    $resultEntry["@id"] = $entry->getId();
                    if (!empty($entry->getLabel())) {
                        $resultEntry["label"] = $entry->getLabelForDisplay($language, $joinChars);
                    }
                    if ($entry instanceof ContentResourceInterface && !empty($entry->getFormat())) {
                        $resultEntry["format"] = $entry->getFormat();
                    }
                    $result[] = $resultEntry;
                }
            }
            return $result;
        }
    }

}

