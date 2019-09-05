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
     * @var \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface
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
                $targetObject = new $targetClass($uriParts[0]);
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
     * @return \Ubl\Iiif\Presentation\Common\Model\Resources\CanvasInterface
     */
    public function getTargetObject() {
        return $this->targetObject;
    }
}

