<?php
namespace iiif\model\properties;

class XYWHFragment
{
    protected $fragment;
    protected $x;
    protected $y;
    protected $width;
    protected $height;
    protected $targetUri;
    protected $targetObject;
    
    protected function __contruct() {
    }

    public static function getFromURI($uri, &$allResources = array(), $targetClass = null) {
        // TODO check if commas or equals sign are contained but url encoded
        if ($uri == null) return null;
        $xywhFragment = new XYWHFragment();
        $uriParts = explode('#', $uri);
        if (strpos($uri, '#xywh=') !== false) {
            $fragment = $uriParts[1];
            $xywhStrings = explode('=',$fragment);
            if (sizeof($xywhStrings)>=2)
            {
                $xywh = explode(',', $xywhStrings[1]);
                if (sizeof($xywhStrings) == 4) {
                    $xywhFragment->x = $xywh[0];
                    $xywhFragment->y = $xywh[1];
                    $xywhFragment->width = $xywh[2];
                    $xywhFragment->height = $xywh[4];
                    $xywhFragment->fragment = $fragment;
                }
            }
        }
        $xywhFragment->targetUri = $uriParts[0];
        if ($targetClass != null) {
            if (array_key_exists($uriParts[0], $allResources)) {
                $xywhFragment->targetObject = &$allResources[$uriParts[0]];
            } else {
                $xywhFragment->targetUri = new $targetClass();
                $allResources[$uriParts[0]] = &$xywhFragment->targetUri; 
            }
        }
        return $xywhFragment;
    }
}

