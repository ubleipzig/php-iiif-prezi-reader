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

interface CollectionInterface extends IiifResourceInterface {
    
    /**
     * @return (ManifestInterface|CollectionInterface)[] All manifests and
     * collections that are directly linked in the collection.
     * Version 2: all of "members", "manifests" and "collections".
     * Version 3: same as "items".
     */
    public function getContainedCollectionsAndManifests();
    
    /**
     * @return CollectionInterface[] All collections that are directly
     * linked in the collection.
     * Version 2: all "members" of type Collection and "collections".
     * Version 3: all "items" of type Collection.
     */
    public function getContainedCollections();
    
    /**
     * @return ManifestInterface[] All manifests that are directly
     * linked in the collection.
     * Version 2: all "members" of type Manifest and "manifests".
     * Version 3: all "items" of type Manifest.
     */
    public function getContainedManifests();
    
}

