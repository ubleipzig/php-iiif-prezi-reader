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

namespace iiif\presentation\v2\model\vocabulary;

class Names {

    // Structures
    const COLLECTIONS = "collections";

    const MANIFESTS = "manifests";

    const MEMBERS = "members";

    const SEQUENCES = "sequences";

    const STRUCTURES = "structures";

    const CANVASES = "canvases";

    const RESOURCES = "resources";

    const OTHER_CONTENT = "otherContent";

    const IMAGES = "images";

    const RANGES = "ranges";

    // Technical
    const CONTEXT = "@context";

    const ID = "@id";

    const TYPE = "@type";

    const FORMAT = "format";

    const HEIGHT = "height";

    const WIDTH = "width";

    const VIEWING_DIRECTION = "viewingDirection";

    const VIEWING_HINT = "viewingHint";

    const NAV_DATE = "navDate";

    // Descriptive, rights
    const LABEL = "label";

    const DESCRIPTION = "description";

    const METADATA = "metadata";

    const THUMBNAIL = "thumbnail";

    const ATTRIBUTION = "attribution";

    const LICENSE = "license";

    const LOGO = "logo";

    // Linking
    const SEE_ALSO = "seeAlso";

    const SERVICE = "service";

    const RELATED = "related";

    const RENDERING = "rendering";

    const WITHIN = "within";

    const START_CANVAS = "startCanvas";

    // Resource specific properties
    const MOTIVATION = "motivation";

    const RESOURCE = "resource";

    const ON = "on";

    const PROFILE = "profile";

    const CHARS = "chars";

    // IIIF Image Profile
    const SUPPORTS = "supports";

    // Value for label/value metadata
    const VALUE = "value";

    // Language Properties
    const AT_LANGUAGE = "@language";

    const AT_VALUE = "@value";
}

