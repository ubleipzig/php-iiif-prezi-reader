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

namespace Ubl\Iiif\Presentation\V3\Model\Resources;

use Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface;
use Ubl\Iiif\Presentation\V3\Model\Properties\PlaceholderAndAccompanyingCanvasTrait;

class Manifest3 extends AbstractIiifResource3 implements ManifestInterface {

    use PlaceholderAndAccompanyingCanvasTrait;
    
    /**
     *
     * @var Canvas3[]
     */
    protected $items;

    /**
     *
     * @var Range3[]
     */
    protected $structures;

    /**
     *
     * @var Annotation3[];
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
     * @var Canvas3
     */
    protected $start;

    /**
     *
     * @return multitype:\Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\Ubl\Iiif\Presentation\V3\Model\Resources\Range3
     */
    public function getStructures() {
        return $this->structures;
    }

    /**
     *
     * @return \Ubl\Iiif\Presentation\V3\Model\Resources\Annotation3[];
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
     * @return \Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3
     */
    public function getStart() {
        return $this->start;
    }

    public function getDefaultCanvases() {
        return $this->items;
        // TODO Use items of first Range with behaviour="sequence" if present
    }

    public function getStartCanvas() {
        if (!isset($this->start)) {
            return null;
        }
        if ($this->start instanceof Canvas3) {
            return $this->start;
        }
        // TODO start could be a selector
    }

    /**
     * 
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        $startCanvas = $this->getStartCanvas();
        if (isset($startCanvas)) {
            return $startCanvas;
        }
        $canvases = $this->getDefaultCanvases();
        return empty($canvases) ? null : $canvases[0];
    }
    
    public function getRootRanges() {
        return $this->getStructures();
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V3\Model\Resources\AbstractIiifResource3::getThumbnailUrl()
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

