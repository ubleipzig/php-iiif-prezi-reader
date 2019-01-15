<?php
namespace iiif\presentation\common;

use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Sequence;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\resources\AnnotationList;
use iiif\presentation\v2\model\resources\Range;
use iiif\presentation\v3\model\resources\Collection3;
use iiif\presentation\v3\model\resources\Manifest3;
use iiif\presentation\v3\model\resources\Canvas3;
use iiif\presentation\v3\model\resources\Range3;
use iiif\presentation\v3\model\resources\Annotation3;
use iiif\presentation\v2\model\resources\Annotation;
use iiif\presentation\v3\model\resources\AnnotationPage3;
use iiif\presentation\v3\model\resources\AnnotationCollection3;
use iiif\presentation\v3\model\resources\ContentResource3;
use iiif\presentation\v2\model\resources\ContentResource;
use iiif\services\ImageInformation1;
use iiif\services\ImageInformation2;
use iiif\services\ImageInformation3;
use iiif\presentation\v3\model\resources\SpecificResource3;

class TypeMap {

    protected static $CLASSES = [
        "http://iiif.io/api/presentation/2#Manifest" => Manifest::class,
        "http://iiif.io/api/presentation/2#Sequence" => Sequence::class,
        "http://iiif.io/api/presentation/2#Canvas" => Canvas::class,
        "http://iiif.io/api/presentation/2#AnnotationList" => AnnotationList::class,
        "http://iiif.io/api/presentation/2#Range" => Range::class,
        "http://iiif.io/api/presentation/2#Layer" => null,
        "http://iiif.io/api/presentation/3#Collection" => Collection3::class,
        "http://iiif.io/api/presentation/3#Manifest" => Manifest3::class,
        "http://iiif.io/api/presentation/3#Canvas" => Canvas3::class,
        "http://iiif.io/api/presentation/3#Range" => Range3::class,
        "http://www.w3.org/ns/oa#Annotation" => [
            "http://iiif.io/api/presentation/3/combined-context.json" => Annotation3::class,
            "http://iiif.io/api/presentation/3/context.json" => Annotation3::class,
            "http://www.w3.org/ns/anno.jsonld" => Annotation3::class,
            "http://iiif.io/api/presentation/2/context.json" => Annotation::class
        ],
        "http://www.w3.org/ns/activitystreams#OrderedCollectionPage" => AnnotationPage3::class,
        "http://www.w3.org/ns/activitystreams#OrderedCollection" => AnnotationCollection3::class,
        "http://www.w3.org/ns/activitystreams#Application" => null,
        "http://purl.org/dc/dcmitype/StillImage" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Image" => ContentResource::class,
        "http://www.w3.org/2011/content#ContentAsText" => ContentResource::class,
        "http://iiif.io/api/image/1/ImageService" => ImageInformation1::class,
        "http://iiif.io/api/image/2/ImageService" => ImageInformation2::class,
        "http://iiif.io/api/image/3/ImageService" => ImageInformation3::class,
        "http://library.stanford.edu/iiif/image-api/1.1/context.json" => ImageInformation1::class,
        "http://iiif.io/api/image/2/context.json" => ImageInformation2::class,
        "http://rdfs.org/sioc/services#Service" => null,
        "http://purl.org/dc/dcmitype/Dataset" => ContentResource3::class,
        "http://purl.org/dc/dcmitype/Text" => ContentResource3::class,
        "http://www.w3.org/ns/oa#SpecificResource" => SpecificResource3::class,
        "http://www.w3.org/ns/oa#TextualBody" => ContentResource3::class,
        "http://www.w3.org/ns/oa#FragmentSelector" => null,
        "http://www.w3.org/ns/oa#PointSelector" => null
    ];

    const SERVICE_TYPES = [
        "http://iiif.io/api/image/1/context.json" => "http://iiif.io/api/image/1/ImageService",
        "http://iiif.io/api/image/2/context.json" => "http://iiif.io/api/image/2/ImageService",
        "http://iiif.io/api/image/3/context.json" => "http://iiif.io/api/image/3/ImageService"
    ];

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

