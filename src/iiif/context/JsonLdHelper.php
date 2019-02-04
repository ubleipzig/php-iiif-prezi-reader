<?php
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

