<?php
namespace iiif\presentation\v2\model\properties;

use iiif\presentation\v2\model\resources\Canvas;

class XYWHFragment {

    /**
     * @var string
     */
    protected $fragment;

    /**
     * @var int
     */
    protected $x;

    /**
     * @var int
     */
    protected $y;

    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * URI / id of the target resource, e.g.
     * Canvas, without the XYWH fragment
     *
     * @var string
     */
    protected $targetUri;

    /**
     * @var Canvas
     */
    protected $targetObject;

    protected function __contruct() {}

    public static function getFromURI($uri, &$allResources = array(), $targetClass = null) {
        // TODO check if commas or equals sign are contained but url encoded
        if ($uri == null)
            return null;
        $xywhFragment = new XYWHFragment();
        $uriParts = explode('#', $uri);
        if (strpos($uri, '#xywh=') !== false) {
            $fragment = $uriParts[1];
            $xywhStrings = explode('=', $fragment);
            if (sizeof($xywhStrings) >= 2) {
                $xywh = explode(',', $xywhStrings[1]);
                if (sizeof($xywh) == 4) {
                    $xywhFragment->x = $xywh[0];
                    $xywhFragment->y = $xywh[1];
                    $xywhFragment->width = $xywh[2];
                    $xywhFragment->height = $xywh[3];
                    $xywhFragment->fragment = $fragment;
                }
            }
        }
        $xywhFragment->targetUri = $uriParts[0];
        if ($targetClass != null) {
            if (array_key_exists($uriParts[0], $allResources)) {
                $xywhFragment->targetObject = &$allResources[$uriParts[0]];
            } else {
                $dummyDoc = [
                    "@context" => "https://iiif.io/api/presentation/2/context.json",
                    "@type" => $targetClass::TYPE,
                    "@id" => $uriParts[0]
                ];
                $targetObject = $targetClass::loadIiifResource($dummyDoc);
                $xywhFragment->targetObject = $targetObject;
                $allResources[$uriParts[0]] = &$xywhFragment->targetObject;
            }
        }
        return $xywhFragment;
    }

    /**
     *
     * @return string
     */
    public function getTargetUri() {
        return $this->targetUri;
    }
    /**
     * @return string
     */
    public function getFragment() {
        return $this->fragment;
    }

    /**
     * @return number
     */
    public function getX() {
        return $this->x;
    }

    /**
     * @return number
     */
    public function getY() {
        return $this->y;
    }

    /**
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return number
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @return \iiif\presentation\v2\model\resources\Canvas
     */
    public function getTargetObject() {
        return $this->targetObject;
    }
}

