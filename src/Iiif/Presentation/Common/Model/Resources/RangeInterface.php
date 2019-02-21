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

namespace Ubl\Iiif\Presentation\Common\Model\Resources;

interface RangeInterface extends IiifResourceInterface {
    
    /**
     * version 2: startCanvas
     * version 3: "start" if "start" is a canvas; otherwise the canvas whose part is given as "start"
     * @return CanvasInterface
     */
    public function getStartCanvas();

    /**
     * @return CanvasInterface Result of getStartCanvas(); if this is null, the first canvas in the range
     */
    public function getStartCanvasOrFirstCanvas();
    
    /**
     * version 2: "ranges", "canvases" and "members" 
     * version 3: items
     * @return (RangeInterface|CanvasInterface)[]
     */
    public function getAllItems();

    /**
     * version 2: all ranges and all members of type Range
     * version 3: all items of type Range 
     * @return RangeInterface[]
     */
    public function getAllRanges();
    
    /**
     * version 2: "canvases" and all "members" of type Canvas
     * version 3: all items of type Canvas
     * @return CanvasInterface[]
     */
    public function getAllCanvases();

    /**
     * version 2: return canvases contained in range and child ranges recursively
     * version 3: return canvases contained in child items of type Range
     * @return CanvasInterface[]
     */
    public function getAllCanvasesRecursively();
    
    /**
     * A top range is a Range with the viewingHint "top" and represents the root of the hierarchy tree.
     *@return bool Range is a version 2 range with viewingHint=top
     */
    public function isTopRange();
    
}

