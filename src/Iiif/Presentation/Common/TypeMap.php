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

namespace Ubl\Iiif\Presentation\Common;

use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Sequence2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Canvas2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Collection2;
use Ubl\Iiif\Presentation\V2\Model\Resources\AnnotationList2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Range2;
use Ubl\Iiif\Presentation\V3\Model\Resources\Collection3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Manifest3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Range3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Annotation3;
use Ubl\Iiif\Presentation\V2\Model\Resources\Annotation2;
use Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationPage3;
use Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationCollection3;
use Ubl\Iiif\Presentation\V3\Model\Resources\ContentResource3;
use Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource2;
use Ubl\Iiif\Services\ImageInformation1;
use Ubl\Iiif\Services\ImageInformation2;
use Ubl\Iiif\Services\ImageInformation3;
use Ubl\Iiif\Presentation\V3\Model\Resources\SpecificResource3;
use Ubl\Iiif\Presentation\V1\Model\Resources\Manifest1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Sequence1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Canvas1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Annotation1;
use Ubl\Iiif\Presentation\V1\Model\Resources\ContentResource1;
use Ubl\Iiif\Presentation\V1\Model\Resources\AnnotationList1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Range1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Layer1;
use Ubl\Iiif\Services\PhysicalDimensions;

/**
 * Map the @type of a JSON-LD resource to a PHP class. If a type IRI is used in more than one
 * API version, the context IRI is used to further differentiate.
 *  
 * FIXME From a RDF perspective, resources might have multiple types.  
 * 
 * @author lutzhelm
 *
 */
class TypeMap {

    protected static $CLASSES = [
        "http://www.shared-canvas.org/ns/Manifest" => Manifest1::class,
        "http://www.shared-canvas.org/ns/Sequence" => Sequence1::class,
        "http://www.shared-canvas.org/ns/Canvas" => Canvas1::class,
        "http://www.shared-canvas.org/ns/AnnotationList" => AnnotationList1::class,
        "http://www.shared-canvas.org/ns/Range" => Range1::class,
        "http://www.shared-canvas.org/ns/Layer" => Layer1::class,
        "http://iiif.io/api/presentation/2#Collection" => Collection2::class,
        "http://iiif.io/api/presentation/2#Manifest" => Manifest2::class,
        "http://iiif.io/api/presentation/2#Sequence" => Sequence2::class,
        "http://iiif.io/api/presentation/2#Canvas" => Canvas2::class,
        "http://iiif.io/api/presentation/2#AnnotationList" => AnnotationList2::class,
        "http://iiif.io/api/presentation/2#Range" => Range2::class,
        "http://iiif.io/api/presentation/2#Layer" => null,
        "http://iiif.io/api/presentation/3#Collection" => Collection3::class,
        "http://iiif.io/api/presentation/3#Manifest" => Manifest3::class,
        "http://iiif.io/api/presentation/3#Canvas" => Canvas3::class,
        "http://iiif.io/api/presentation/3#Range" => Range3::class,
        "http://www.w3.org/ns/oa#Annotation" => [
            "http://www.shared-canvas.org/ns/context.json" => Annotation1::class,
            "http://iiif.io/api/presentation/1/context.json" => Annotation1::class,
            "http://iiif.io/api/presentation/2/context.json" => Annotation2::class,
            "http://iiif.io/api/presentation/3/combined-context.json" => Annotation3::class,
            "http://iiif.io/api/presentation/3/context.json" => Annotation3::class,
            "http://www.w3.org/ns/anno.jsonld" => Annotation3::class
        ],
        "http://www.w3.org/ns/activitystreams#OrderedCollectionPage" => AnnotationPage3::class,
        "http://www.w3.org/ns/activitystreams#OrderedCollection" => AnnotationCollection3::class,
        "http://www.w3.org/ns/activitystreams#Application" => null,
        "http://purl.org/dc/dcmitype/StillImage" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Image" => [
            "http://www.shared-canvas.org/ns/context.json" => ContentResource1::class,
            "http://iiif.io/api/presentation/1/context.json" => ContentResource1::class,
            "http://iiif.io/api/presentation/2/context.json" => ContentResource2::class,
        ],
        "http://www.w3.org/2011/content#ContentAsText" => [
            "http://www.shared-canvas.org/ns/context.json" => ContentResource1::class,
            "http://iiif.io/api/presentation/1/context.json" => ContentResource1::class,
            "http://iiif.io/api/presentation/2/context.json" => ContentResource2::class,
        ],
        "http://iiif.io/api/image/1/ImageService" => ImageInformation1::class,
        "http://iiif.io/api/image/2/ImageService" => ImageInformation2::class,
        "http://iiif.io/api/image/3/ImageService" => ImageInformation3::class,
        "http://library.stanford.edu/iiif/image-api/1.1/context.json" => ImageInformation1::class,
        "http://iiif.io/api/image/2/context.json" => ImageInformation2::class,
        "http://rdfs.org/sioc/services#Service" => null,
        "http://purl.org/dc/dcmitype/Dataset" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Text" => ContentResource3::class,
        // TODO
        "http://www.w3.org/ns/oa#SpecificResource" => SpecificResource3::class,
        "http://www.w3.org/ns/oa#TextualBody" => ContentResource3::class,
        "http://www.w3.org/ns/oa#FragmentSelector" => null,
        "http://www.w3.org/ns/oa#PointSelector" => null,
        "http://iiif.io/api/image/2#Size" => null,
        "http://iiif.io/api/image/3#Size" => null,
        "http://iiif.io/api/image/2#Tile" => null,
        "http://iiif.io/api/image/3#Tile" => null,
        // FIXME at the moment, we only have a JSON-LD context URI instead of a name from a vocabulary
        "http://iiif.io/api/annex/services/physdim/1/context.json" => PhysicalDimensions::class
    ];

    const SERVICE_TYPES_BY_CONTEXT = [
        "http://library.stanford.edu/iiif/image-api/1.1/context.json" => "http://iiif.io/api/image/1/ImageService",
        "http://iiif.io/api/image/1/context.json" => "http://iiif.io/api/image/1/ImageService",
        "http://iiif.io/api/image/2/context.json" => "http://iiif.io/api/image/2/ImageService",
        "http://iiif.io/api/image/3/context.json" => "http://iiif.io/api/image/3/ImageService",
        // Not explicit @type, use profile instead 
        "http://iiif.io/api/annex/services/physdim/1/context.json" => "http://iiif.io/api/annex/services/physdim/1/context.json"
    ];
    
    const SERVICE_TYPES_BY_PROFILE = [
        "http://library.stanford.edu/iiif/image-api/1.1/compliance.html#level0" => "http://iiif.io/api/image/1/ImageService",
        "http://library.stanford.edu/iiif/image-api/1.1/compliance.html#level1" => "http://iiif.io/api/image/1/ImageService",
        "http://library.stanford.edu/iiif/image-api/1.1/compliance.html#level2" => "http://iiif.io/api/image/1/ImageService",
        "http://library.stanford.edu/iiif/image-api/1.1/conformance.html#level0" => "http://iiif.io/api/image/1/ImageService",
        "http://library.stanford.edu/iiif/image-api/1.1/conformance.html#level1" => "http://iiif.io/api/image/1/ImageService",
        "http://library.stanford.edu/iiif/image-api/1.1/conformance.html#level2" => "http://iiif.io/api/image/1/ImageService"
    ];

    public static function getClassForContext($contextIri, $context) {
        if ($contextIri == null || ! array_key_exists($contextIri, self::SERVICE_TYPES_BY_CONTEXT)) {
            return null;
        }
        return self::getClassForType(self::SERVICE_TYPES_BY_CONTEXT[$contextIri], $context);
    }
    
    public static function getClassForType($typeIri, $context) {
        if ($typeIri == null || ! array_key_exists($typeIri, self::$CLASSES))
            return null;
        if (is_array(self::$CLASSES[$typeIri])) {
            return self::$CLASSES[$typeIri][$context->getContextIri()];
        } else {
            return self::$CLASSES[$typeIri];
        }
    }
}

