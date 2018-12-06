<?php
namespace iiif\context;

class JsonLdHelper {
    
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

