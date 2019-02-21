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

namespace iiif\presentation\v3\model\resources;

use iiif\presentation\common\model\resources\RangeInterface;
use iiif\presentation\v3\model\properties\PlaceholderAndAccompanyingCanvasTrait;

class Range3 extends AbstractIiifResource3 implements RangeInterface {

    use PlaceholderAndAccompanyingCanvasTrait;
    
    /**
     *
     * @var (Range3|Canvas3|SpecificResource3)[]
     */
    protected $items;

    /**
     *
     * @var Annotation3[]
     */
    protected $annotations;

    /**
     *
     * @var string
     */
    protected $navDate;

    /**
     *
     * @var string
     */
    protected $viewingDirection;

    /**
     *
     * @var (Canvas3|SpecificResource3)
     */
    protected $start;

    /**
     *
     * @var AnnotationCollection3
     */
    protected $supplementary;

    /**
     *
     * @return multitype:Ambigous <\iiif\presentation\v3\model\resources\Range3, \iiif\presentation\v3\model\resources\Canvas3, \iiif\presentation\v3\model\resources\SpecificResource3>
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\Annotation3
     */
    public function getAnnotations() {
        return $this->annotations;
    }

    /**
     *
     * @return string
     */
    public function getNavDate() {
        return $this->navDate;
    }

    /**
     *
     * @return string
     */
    public function getViewingDirection() {
        return $this->viewingDirection;
    }

    /**
     *
     * @return (\iiif\presentation\v3\model\resources\Canvas3|\iiif\presentation\v3\model\resources\SpecificResource3)
     */
    public function getStart() {
        return $this->start;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\AnnotationCollection3
     */
    public function getSupplementary() {
        return $this->supplementary;
    }

    public function getAllCanvases() {
        $allCanvases = [];
        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item instanceof Canvas3) {
                    $allCanvases[] = $item;
                }
            }
        }
        return $allCanvases;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllCanvasesRecursively()
     */
    public function getAllCanvasesRecursively() {
        // TODO untested
        $result = [];
        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item instanceof Canvas3) {
                    $result[] = $item;
                }
                elseif ($item instanceof Range3) {
                    $childCanvases = $item->getAllCanvasesRecursively();
                    if (!empty($childCanvases)) {
                        $result = array_merge($result, $childCanvases);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllItems()
     */
    public function getAllItems() {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getAllRanges()
     */
    public function getAllRanges() {
        $result = [];
        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item instanceof Range3) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        if (isset($this->start) && $this->start instanceof Canvas3) {
            return $this->start;
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\RangeInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        if ($this->getStartCanvas() != null) {
            return $this->getStartCanvas();
        }
        $canvases = $this->getAllCanvasesRecursively();
        if (!empty($canvases)) {
            return $canvases[0];
        }
        return null;
    }

    public function isTopRange() {
        return false;
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v3\model\resources\AbstractIiifResource3::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result =  parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        if ($this->getStartCanvasOrFirstCanvas()!=null) {
            return $this->getStartCanvasOrFirstCanvas()->getThumbnailUrl();
        }
        return null;
    }


    
}

