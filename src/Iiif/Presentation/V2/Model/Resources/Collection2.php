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

use Ubl\Iiif\Presentation\V2\Model\Properties\NavDateTrait;
use Ubl\Iiif\Presentation\Common\Model\Resources\CollectionInterface;
use Ubl\Iiif\Presentation\Common\Model\Resources\ManifestInterface;

class Collection2 extends AbstractIiifResource2 implements CollectionInterface {
    use NavDateTrait;

    const TYPE = "sc:Collection";

    /**
     *
     * @var Collection2[]
     */
    protected $collections = array();

    /**
     *
     * @var Manifest2[]
     */
    protected $manifests = array();

    protected $members = array();

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\V2\Model\Resources\AbstractIiifResource2::getPropertyMap()
     */
    protected function getPropertyMap() {
        return array_merge(parent::getPropertyMap(),[
            "http://iiif.io/api/presentation/2#presentationDate" => "navDate",
            "http://iiif.io/api/presentation/2#hasCollections" => "collections",
            "http://iiif.io/api/presentation/2#hasManifests" => "manifests",
            "http://iiif.io/api/presentation/2#hasParts" => "members"
        ]);
    }

    /**
     * {@inheritDoc}
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CollectionInterface::getContainedCollections()
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CollectionInterface::getContainedCollectionsAndManifests()
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
     * @see \Ubl\Iiif\Presentation\Common\Model\Resources\CollectionInterface::getContainedManifests()
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

