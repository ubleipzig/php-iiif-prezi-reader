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

namespace Ubl\Iiif\Presentation\V2\Model\Resources;

use Ubl\Iiif\Presentation\V2\Model\Properties\StartCanvasTrait;
use Ubl\Iiif\Presentation\V2\Model\Properties\ViewingDirectionTrait;
use Ubl\Iiif\Presentation\Common\Model\Resources\RangeInterface;
use Ubl\Iiif\Presentation\V2\Model\Constants\ViewingHintValues;

class Range extends AbstractIiifResource2 implements RangeInterface {
    use ViewingDirectionTrait;
    use StartCanvasTrait;

    const TYPE = "sc:Range";

    /**
     *
     * @var Range[]
     */
    protected $ranges = array();

    /**
     *
     * @var Canvas[]
     */
    protected $canvases = array();

    /**
     * 
     * @var (Canvas|Range)[]
     */
    protected $members = array();

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(),[
            "http://iiif.io/api/presentation/2#hasCanvases" => "canvases",
            "http://iiif.io/api/presentation/2#hasParts" => "members",
            "http://iiif.io/api/presentation/2#hasRanges" => "ranges",
            "http://iiif.io/api/presentation/2#hasStartCanvas" => "startCanvas",
            "http://iiif.io/api/presentation/2#viewingDirection" => "viewingDirection"
        ]);
    }

    protected function getStringResources() {
        return [
            "ranges" => Range::class,
            "canvases" => Canvas::class,
            "startCanvas" => Canvas::class
        ];
    }

    /**
     *
     * @return multitype:\Ubl\Iiif\Model\Resources\Range
     */
    public function getRanges() {
        return $this->ranges;
    }

    /**
     *
     * @return multitype:\Ubl\Iiif\Model\Resources\Canvas
     */
    public function getCanvases() {
        return $this->canvases;
    }

    /**
     * @return (Canvas|Range)[]
     */
    public function getMembers() {
        return $this->members;
    }

    public function getStartCanvasOrFirstCanvas() {
        if (isset($this->startCanvas)) {
            return $this->startCanvas;
        } elseif (isset($this->canvases) && sizeof($this->canvases) > 0) {
            return $this->canvases[0];
        } elseif (isset($this->ranges) && sizeof($this->ranges) > 0) {
            return $this->ranges[0]->getStartCanvasOrFirstCanvas();
        } elseif (isset($this->members) && sizeof($this->members) > 0) {
            foreach ($this->members as $member) {
                if ($member instanceof Canvas) {
                    return $member;
                } elseif ($member instanceof Range) {
                    return $member->getStartCanvasOrFirstCanvas();
                }
            }
        }
        return null;
    }

    /**
     * 
     * @return Canvas[]
     */
    public function getAllCanvasesRecursively() {
        $allCanvases = [];
        if (isset($this->canvases) && sizeof($this->canvases) > 0) {
            $allCanvases = $this->canvases;
        }
        if (isset($this->ranges) && sizeof($this->ranges) > 0) {
            foreach ($this->ranges as $range)
                $allCanvases = array_merge($allCanvases, $range->getAllCanvases());
        }
        if (isset($this->members) && sizeof($this->members) > 0) {
            foreach ($this->members as $member) {
                if ($member instanceof Canvas) {
                    $allCanvases[] = $member;
                }
                if ($member instanceof Range) {
                    $allCanvases = array_merge($allCanvases, $member->getAllCanvases());
                }
            }
        }
        return $allCanvases;
    }
    
    public function getMemberRangesAndRanges() {
        $result = [];
        if (!empty($this->ranges)) {
            $result = $this->ranges;
        }
        if (!empty($this->members)) {
            foreach ($this->members as $member) {
                if ($member instanceof Range) {
                    $result[] = $member;
                }
            }
        }
        return $result;
    }
    
    public function getAllRanges() {
        return $this->getMemberRangesAndRanges();
    }
    
    public function getAllCanvases() {
        $result = [];
        if (!empty($this->canvases)) {
            $result = $this->canvases;
        }
        if (!empty($this->members)) {
            foreach ($this->members as $member) {
                if ($member instanceof Canvas) {
                    $result[] = $canvas;
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\RangeInterface::getAllItems()
     */
    public function getAllItems() {
        $items = [];
        if ($this->getRanges()!=null) {
            foreach ($this->getRanges() as $range) {
                $items[] = $range;
            }
        }
        if ($this->getCanvases()!=null) {
            foreach ($this->getCanvases() as $canvas) {
                $items[] = $canvas;
            }
        }
        if ($this->getMembers()!=null) {
            foreach ($this->getMembers() as $member) {
                $items[] = $member;
            }
        }
        return $items;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        if ($this->getThumbnail()!=null) {
            parent::getThumbnailUrl();
            if ($this->getThumbnail() )
            if (is_string($this->getThumbnail())) {
                return $this->getThumbnail();
            }
        } else {
            $start = $this->getStartCanvasOrFirstCanvas();
            if ($start!=null) {
                return $start->getThumbnailUrl();
            }
        }
        return null;
    }
    
    public function isTopRange() {
        return isset($this->viewingHint) && $this->viewingHint == ViewingHintValues::TOP;
    }
}

