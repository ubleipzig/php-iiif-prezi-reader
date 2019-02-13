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

namespace iiif\context;

class JsonLdHelper {
    
    /**
     * JSON-LD distinguishes between arrays and dictionaries. A PHP equivalent to a JSON-LD array is
     * an array with a consecutive numeric index, starting at 0.
     * @param mixed $array
     * @return boolean
     */
    public static function isSequentialArray($array) {
        if (! is_array($array)) {
            return false;
        }
        $lastIndex = sizeof($array) - 1;
        foreach ($array as $key => $value) {
            if (! is_int($key) || $key < 0 || $key > $lastIndex) {
                return false;
            }
        }
        return true;
    }

    /**
     * JSON-LD distinguishes between arrays and dictionaries. A PHP equivalent to a JSON-LD dictionary is
     * an associative array with only strings as key.
     * @param mixed $dictionary
     * @return boolean
     */
    public static function isDictionary($dictionary) {
        if ($dictionary == null || ! is_array($dictionary)){
            return false;
        }
        foreach ($dictionary as $key => $value) {
            if (! is_string($key))
            {
                return false;
            }
            if ($value != null && ! is_scalar($value) && ! is_array($value)) {
                return false;
            }
        }
        return true;
    }

}

