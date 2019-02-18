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

namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\NavDateTrait;
use iiif\presentation\common\model\resources\CollectionInterface;
use iiif\presentation\common\model\resources\ManifestInterface;

class Collection extends AbstractIiifResource2 implements CollectionInterface {
    use NavDateTrait;

    const TYPE = "sc:Collection";

    protected $navDate;

    /**
     *
     * @var Collection[]
     */
    protected $collections = array();

    /**
     *
     * @var Manifest[]
     */
    protected $manifests = array();

    protected $members = array();

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CollectionInterface::getContainedCollections()
     */
    public function getContainedCollections() {
        $containedCollections = empty($this->collections) ? [] : $this->collections;
        if (!empty($this->members)) {
            foreach ($this->members as $member) {
                if ($member instanceof CollectionInterface) {
                    $containedCollections[] = $member;
                }
            }
        }
        return $containedCollections;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CollectionInterface::getContainedCollectionsAndManifests()
     */
    public function getContainedCollectionsAndManifests() {
        $result = [];
        if (!empty($this->members)) {
            $result = array_merge($result, $this->members);
        }
        if (!empty($this->collections)) {
            $result = array_merge($result, $this->collections);
        }
        if (!empty($this->manifests)) {
            $result = array_merge($result, $this->manifests);
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @see \iiif\presentation\common\model\resources\CollectionInterface::getContainedManifests()
     */
    public function getContainedManifests() {
        $containedManifests = empty($this->manifests) ? [] : $this->manifests;
        if (!empty($this->members)) {
            foreach ($this->members as $member) {
                if ($member instanceof ManifestInterface) {
                    $containedManifests[] = $member;
                }
            }
        }
        return $containedManifests;
    }

}

