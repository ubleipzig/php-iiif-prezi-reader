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

namespace iiif\presentation\common\model\resources;

interface ManifestInterface extends IiifResourceInterface {
    
    /**
     * version 2: canvases of default sequence
     * version 3: items of the first range with "sequence" behaviour; otherwise items of manifest 
     * @return CanvasInterface[]
     */
    public function getDefaultCanvases();

    /**
     * version 2: first range marked as top or any ranges that are no children of other ranges
     * version 3: ...
     * @return RangeInterface[]
     */
    public function getRootRanges();
    
    /**
     * version 2: startCanvas
     * version 3: "start" if "start" is a canvas; otherwise the canvas whose part is given as "start"
     * @return CanvasInterface
     */
    public function getStartCanvas();
    
    /**
     * @return CanvasInterface value of getStartCanvas() or first canvas in getDefaultCanvases()
     */
    public function getStartCanvasOrFirstCanvas();
    
    /**
     * @return RangeInterface[]
     */
    public function getStructures();
    
    /**
     * @param string $id
     * @return IiifResourceInterface The PHP object representation of the IIIF resource the ID $id.
     * If the manifest does not contain such a resource, return null.  
     */
    public function getContainedResourceById($id);
    
}

