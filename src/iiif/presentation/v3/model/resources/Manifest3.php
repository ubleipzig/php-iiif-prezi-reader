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

use iiif\presentation\common\model\resources\ManifestInterface;

class Manifest3 extends AbstractIiifResource3 implements ManifestInterface {

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
     * @var Canvas3
     */
    protected $posterCanvas;

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
     * @return multitype:\iiif\presentation\v3\model\resources\Canvas3
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\Range3
     */
    public function getStructures() {
        return $this->structures;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Annotation3[];
     */
    public function getAnnotations() {
        return $this->annotations;
    }

    /**
     *
     * @return \iiif\presentation\v3\model\resources\Canvas3
     */
    public function getPosterCanvas() {
        return $this->posterCanvas;
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
     * @return \iiif\presentation\v3\model\resources\Canvas3
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
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvasOrFirstCanvas()
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

