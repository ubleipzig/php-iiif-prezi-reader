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

use Ubl\Iiif\Presentation\V2\Model\Properties\ViewingDirectionTrait;
use Ubl\Iiif\Presentation\V2\Model\Properties\StartCanvasTrait;

class Sequence extends AbstractIiifResource2 {
    use ViewingDirectionTrait;
    use StartCanvasTrait;

    const TYPE = "sc:Sequence";

    /**
     *
     * @var Canvas[]
     */
    protected $canvases = array();

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(), [
            "http://iiif.io/api/presentation/2#hasCanvases" => "canvases",
            "http://iiif.io/api/presentation/2#hasStartCanvas" => "startCanvas",
            "http://iiif.io/api/presentation/2#viewingDirection" => "viewingDirection"
        ]);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources() {
        return [
            "startCanvas" => Canvas::class
        ];
    }

    /**
     *
     * @return Canvas[]
     */
    public function getCanvases() {
        return $this->canvases;
    }

    public function getStartCanvasOrFirstCanvas() {
        if (isset($this->startCanvas)) {
            return $this->startCanvas;
        } elseif (isset($this->canvases) && sizeof($this->canvases) > 0) {
            return $this->canvases[0];
        }
    }
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $startCanvas = $this->getStartCanvasOrFirstCanvas();
        if ($startCanvas != null) {
            return $startCanvas->getThumbnailUrl();
        }
        return null;
    }

    
    
}

