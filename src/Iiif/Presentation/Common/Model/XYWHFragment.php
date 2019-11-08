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

namespace Ubl\Iiif\Presentation\Common\Model;

/**
 * XYWHFragment represents a spatial media fragment identifier (e.g. xywh=1000,500,200,100 in
 * http://example.org/manifest1/canvas/canvas1#xywh=1000,500,200,100). It holds the dimensions
 * as well as the target resource, the latter both as id and as PHP object. It supports only
 * absolute values without units, so fragments like xywh=pixel:160,120,320,240 or
 * xywh=percent:25,25,50,50 are not (yet) supported.
 * 
 * @author Lutz Helm <helm@ub.uni-leipzig.de>
 *
 */
class XYWHFragment {

    /**
     * The fragment in it's original form, e.g. "xywh=1000,500,200,100"
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
     * URI / id of the target resource, e.g. Canvas, without the XYWH fragment
     *
     * @var string
     */
    protected $targetUri;

    /**
     * PHP object representation of the resource that is targeted
     * @var \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface
     */
    protected $targetObject;

    protected function __construct() {}

    /**
     * 
     * @param string $uri
     * @param array $allResources
     * @param string $targetClass
     * @return NULL|\Ubl\Iiif\Presentation\Common\Model\XYWHFragment
     */
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
                    $xywhFragment->x = $xywh[0] * 1;
                    $xywhFragment->y = $xywh[1] * 1;
                    $xywhFragment->width = $xywh[2] * 1;
                    $xywhFragment->height = $xywh[3] *  1;
                    $xywhFragment->fragment = $fragment;
                }
            }
        }
        $xywhFragment->targetUri = $uriParts[0];
        if ($targetClass != null) {
            if (array_key_exists($uriParts[0], $allResources)) {
                $xywhFragment->targetObject = &$allResources[$uriParts[0]];
            } else {
                $targetObject = new $targetClass($uriParts[0]);
                $xywhFragment->targetObject = $targetObject;
                $allResources[$uriParts[0]] = &$xywhFragment->targetObject;
            }
        }
        return $xywhFragment;
    }

    /**
     * @return string URI of the targeted resource without the fragment identifier
     */
    public function getTargetUri() {
        return $this->targetUri;
    }
    /**
     * @return string The original fragment identifier as it appears in the URI
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
     * @return \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface Object
     * representation of the targeted resource
     */
    public function getTargetObject() {
        return $this->targetObject;
    }
}

