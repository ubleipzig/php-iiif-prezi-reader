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

use Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity;
use Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface;
use Ubl\Iiif\Presentation\V2\Model\Constants\ViewingHintValues;
use Ubl\Iiif\Presentation\V2\Model\Properties\NavDateTrait;
use Ubl\Iiif\Presentation\V2\Model\Properties\ViewingDirectionTrait;

class Manifest2 extends AbstractIiifResource2 implements ManifestInterface {
    use NavDateTrait;
    use ViewingDirectionTrait;

    const CONTEXT = "http://iiif.io/api/presentation/2/context.json";

    /**
     *
     * @var Sequence2[]
     */
    protected $sequences = array();

    /**
     *
     * @var Range2[]
     */
    protected $structures = array();

    /**
     * The top structures in the hierarchy.
     * Either a single range with the viewingHint property set to "top" or every range that is not contained in another range
     *
     * @var Range2[]
     */
    protected $rootRanges;

    /**
     *
     * @return Sequence2[]:
     */
    public function getSequences() {
        return $this->sequences;
    }

    /**
     *
     * @return Range2[]
     */
    public function getStructures() {
        return $this->structures;
    }

    /**
     * Top structure in hierarchy; either the Range marked with viewingHint=top or the one that is not part of another range
     *
     * @return Range2[]
     */
    public function getRootRanges() {
        // TODO untested
        if ($this->rootRanges == null) {
            $this->rootRanges = array();
            $ranges = [];
            foreach ($this->structures as $range) {
                $ranges[] = $range->getId();
            }
            foreach ($this->structures as $range) {
                if ($range->getViewingHint() == ViewingHintValues::TOP) {
                    // if there is a top structure, use it!
                    $this->rootRanges[] = $range;
                    break;
                }
                foreach ($this->structures as $r) {
                    if (in_array($range, $r->getRanges())) {
                        $key = array_search($range->getId(), $ranges);
                        unset($ranges[$key]);
                    }
                }
            }
            if (sizeof($ranges) > 0 && sizeof($this->rootRanges)==0) {
                foreach ($ranges as $rangeId) {
                    $this->rootRanges[] = $this->getContainedResourceById($rangeId);
                }
            }
        }
        return $this->rootRanges;
    }

    public function getContainedResourceById($id): ?AbstractIiifEntity {
        if (!is_string($id)) {
            return null;
        }
        if (array_key_exists($id, $this->containedResources)) {
            return $this->containedResources[$id];
        }
        return null;
    }

    private function getDefaultSequence()
    {
        if (!empty($this->sequences)) {
            return $this->sequences[0];
        }
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface::getDefaultCanvases()
     */
    public function getDefaultCanvases(): ?array
    {
        if ($this->getDefaultSequence() != null) {
            return $this->getDefaultSequence()->getCanvases();
        }
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface::getStartCanvas()
     */
    public function getStartCanvas() {
        if ($this->getDefaultSequence() != null) {
            return $this->getDefaultSequence()->getStartCanvas();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface::getStartCanvasOrFirstCanvas()
     */
    public function getStartCanvasOrFirstCanvas() {
        if ($this->getDefaultSequence() != null) {
            return $this->getDefaultSequence()->getStartCanvasOrFirstCanvas();
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface::getThumbnailUrl()
     */
    public function getThumbnailUrl(): ?string
    {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $defaultSequence = $this->getDefaultSequence();
        if ($defaultSequence != null) {
            return $defaultSequence->getThumbnailUrl();
        }
        return null;
    }

}


