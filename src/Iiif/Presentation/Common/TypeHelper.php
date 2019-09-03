<?php
namespace Ubl\Iiif\Presentation\Common;

use Ubl\Iiif\Context\Keywords;
use Ubl\Iiif\Presentation\V1\Model\Resources\Annotation1;
use Ubl\Iiif\Presentation\V1\Model\Resources\AnnotationList1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Canvas1;
use Ubl\Iiif\Presentation\V1\Model\Resources\ContentResource1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Layer1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Manifest1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Range1;
use Ubl\Iiif\Presentation\V1\Model\Resources\Sequence1;
use Ubl\Iiif\Presentation\V2\Model\Resources\Annotation2;
use Ubl\Iiif\Presentation\V2\Model\Resources\AnnotationList2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Canvas2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Collection2;
use Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Range2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Sequence2;
use Ubl\Iiif\Presentation\V3\Model\Resources\Annotation3;
use Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationCollection3;
use Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationPage3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Collection3;
use Ubl\Iiif\Presentation\V3\Model\Resources\ContentResource3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Manifest3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Range3;
use Ubl\Iiif\Presentation\V3\Model\Resources\SpecificResource3;
use Ubl\Iiif\Services\ImageInformation1;
use Ubl\Iiif\Services\ImageInformation2;
use Ubl\Iiif\Services\ImageInformation3;
use Ubl\Iiif\Services\PhysicalDimensions;

class TypeHelper {
    
    const PRESENTATION1_TYPES = [
        "sc:Manifest" => Manifest1::class,
        "sc:Sequence" => Sequence1::class,
        "sc:Canvas" => Canvas1::class,
        "sc:AnnotationList" => AnnotationList1::class,
        "sc:Range" => Range1::class,
        "sc:Layer" => Layer1::class,
        "oa:Annotation" => Annotation1::class,
        "dctypes:Image" => ContentResource1::class,
        "cnt:ContentAsText" => ContentResource1::class,
    ];
    
    const IMAGE1_TYPES = [
        
    ];
    
    const PRESENTATION2_TYPES = [
        "sc:Collection" => Collection2::class,
        "sc:Manifest" => Manifest2::class,
        "sc:Sequence" => Sequence2::class,
        "sc:Canvas" => Canvas2::class,
        "sc:AnnotationList" => AnnotationList2::class,
        "sc:Range" => Range2::class,
        "sc:Layer" => null,
        "oa:Annotation" => Annotation2::class,
        "dctypes:Image" => ContentResource2::class,
        "cnt:ContentAsText" => ContentResource2::class,
    ];
        
    const IMAGE2_TYPES = [
        "iiif:ImageProfile" => null,
    ];
    
    const PRESENTATION3_TYPES = [
        "Collection" => Collection3::class,
        "Manifest" => Manifest3::class,
        "Canvas" => Canvas3::class,
        "Range" => Range3::class,
        "Annotation" => Annotation3::class,
        "AnnotationPage" => AnnotationPage3::class,
        "AnnotationCollection" => AnnotationCollection3::class,
        "SpecificResource" => SpecificResource3::class,
        "Dataset" => ContentResource3::class,
        "Text" => ContentResource3::class,
        "Image" => ContentResource3::class,
        "Video" => ContentResource3::class,
        "Audio" => ContentResource3::class,
        "ImageService1" => ImageInformation1::class,
        "ImageService2" => ImageInformation2::class,
        "ImageService3" => ImageInformation3::class,
    ];
    
    const IMAGE3_TYPES = [
        "ImageService3" => ImageInformation3::class,
        "Size" => null,
        "Tile" => null,
    ];

    const TYPES = [
        "http://www.shared-canvas.org/ns/context.json" => self::PRESENTATION1_TYPES,
        "http://iiif.io/api/presentation/1/context.json" => self::PRESENTATION1_TYPES,
        "http://iiif.io/api/presentation/2/context.json" => self::PRESENTATION2_TYPES,
        "http://iiif.io/api/presentation/3/context.json" => self::PRESENTATION3_TYPES,
        "http://iiif.io/api/image/2/context.json" => self::IMAGE2_TYPES,
        "http://iiif.io/api/image/3/context.json" => self::IMAGE3_TYPES,
    ];
    
    const CONTEXT_TYPES = [
        "http://library.stanford.edu/iiif/image-api/1.1/context.json" => ImageInformation1::class,
        "http://iiif.io/api/image/2/context.json" => ImageInformation2::class,
        "http://iiif.io/api/image/3/context.json" => ImageInformation3::class,
        "http://iiif.io/api/annex/services/physdim/1/context.json" => PhysicalDimensions::class,
    ];
    
    const CONTEXT_IRIS = [
        "http://library.stanford.edu/iiif/image-api/1.1/context.json",
        "http://iiif.io/api/image/1/context.json",
        "http://iiif.io/api/image/2/context.json",
        "http://iiif.io/api/image/3/context.json",
        "http://www.shared-canvas.org/ns/context.json",
        "http://iiif.io/api/presentation/2/context.json",
        "http://iiif.io/api/presentation/3/context.json",
        "http://iiif.io/api/annex/services/physdim/1/context.json",
    ];

    public static function getKeywordOrAlias($context, $keyword) {
        if ($context === "http://iiif.io/api/presentation/3/context.json") {
            switch ($keyword) {
                case Keywords::TYPE:
                    return "type";
                case Keywords::ID:
                    return "id";
                default:
            }
        }
        return $keyword;
    }
    
    public static function getClass($dictionary, $context) {
        $type = self::getType($dictionary, $context);
        $typeClass = null;
        if (isset($type) && array_key_exists($context, self::TYPES) && array_key_exists($type, self::TYPES[$context])) {
            $typeClass = self::TYPES[$context][$type];
        } elseif (!isset($typeClass) && ($localContext = self::getIiifContextIri($dictionary)) != null && array_key_exists($localContext, self::CONTEXT_TYPES)) {
            $typeClass = self::CONTEXT_TYPES[$localContext];
        }
        return $typeClass;
    }
    
    public static function getType($dictionary, $context) {
        $typeOrAlias = self::getKeywordOrAlias($context, Keywords::TYPE);
        if (array_key_exists($typeOrAlias, $dictionary)) {
            return $dictionary[$typeOrAlias];
        }
    }
    
    public static function isIiifContext($context) {
        if (array_search($context, self::CONTEXT_IRIS) !== false) {
            return true;
        }
        return false;
    }
    
    public static function getIiifContextIri($dictionary) {
        if (array_key_exists(Keywords::CONTEXT, $dictionary)) {
            $localContext = $dictionary[Keywords::CONTEXT];
            if (is_array($localContext)) {
                foreach ($localContext as $c) {
                    if (TypeHelper::isIiifContext($c)) {
                        return $c;
                    }
                }
            } else {
                return $dictionary[Keywords::CONTEXT];
            }
        }
        return null;
    }
}

