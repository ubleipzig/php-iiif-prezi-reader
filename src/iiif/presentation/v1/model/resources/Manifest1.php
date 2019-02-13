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

namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\resources\ManifestInterface;

class Manifest1 extends AbstractDescribableResource1 implements ManifestInterface {
    
    /**
     * 
     * @var Sequence1[]
     */
    protected $sequences;
    
    /**
     * 
     * @var Range1[]
     */
    protected $structures;
    
    protected function executeAfterLoading() {
        foreach ($this->containedResources as $resource) {
            if ($resource instanceof Range1) {
                $resource->initTreeHierarchy();
            }
        }
    }

    /**
     * @return multitype:\iiif\presentation\v1\model\resources\Sequence1 
     */
    public function getSequences() {
        return $this->sequences;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStructures()
     */
    public function getStructures() {
        return $this->structures;
    }
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getContainedResourceById()
     */
    public function getContainedResourceById($id) {
        if ($this->containedResources != null && array_key_exists($id, $this->containedResources)) {
            return $this->containedResources[$id];
        }
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getDefaultCanvases()
     */
    public function getDefaultCanvases() {
        if (!empty($this->sequences)) {
            return $this->sequences[0]->getCanvases();
        }
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getRootRanges()
     */
    public function getRootRanges() {
        $result = [];
        if (!empty($this->structures)) {
            foreach ($this->structures as $range) {
                if (empty($range->getWithin()) || (!$range->getWithin() instanceof Range1)) {
                    $result[] = &$range;
                }
            }
        }
        return $result;
    }

    /**
     * Metadata API has no property like "start" (http://iiif.io/api/presentation/3#start) or "startCanvas" (http://iiif.io/api/presentation/2#hasStartCanvas)
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\ManifestInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        if (!empty($this->getDefaultCanvases())) {
            return $this->getDefaultCanvases()[0];
        }
        return null;
    }

}