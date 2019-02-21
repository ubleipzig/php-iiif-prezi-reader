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

use iiif\presentation\common\model\resources\CollectionInterface;
use iiif\presentation\common\model\resources\ManifestInterface;
use iiif\presentation\v3\model\properties\PlaceholderAndAccompanyingCanvasTrait;

class Collection3 extends AbstractIiifResource3 implements CollectionInterface {
    
    use PlaceholderAndAccompanyingCanvasTrait;

    /**
     *
     * @var (Collection3|Manifest3)[]
     */
    protected $items;

    /**
     *
     * @var AnnotationPage3[]
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
     * @return multitype:Ambigous <\iiif\presentation\v3\model\resources\Collection3, \iiif\presentation\v3\model\resources\Manifest3>
     */
    public function getItems() {
        return $this->items;
    }

    /**
     *
     * @return multitype:\iiif\presentation\v3\model\resources\AnnotationPage3
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
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CollectionInterface::getContainedCollections()
     */
    public function getContainedCollections() {
        $result = [];
        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item instanceof CollectionInterface) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CollectionInterface::getContainedCollectionsAndManifests()
     */
    public function getContainedCollectionsAndManifests() {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CollectionInterface::getContainedManifests()
     */
    public function getContainedManifests() {
        $result = [];
        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                if ($item instanceof ManifestInterface) {
                    $result[] = $item;
                }
            }
        }
        return $result;
    }




}