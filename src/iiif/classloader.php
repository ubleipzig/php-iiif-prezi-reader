<?php

// Use this file if you don't want to or can't rely on composer or other autoloaders

require_once (__DIR__ . "/context/IRI.php");
require_once (__DIR__ . "/context/JsonLdContext.php");
require_once (__DIR__ . "/context/JsonLdHelper.php");
require_once (__DIR__ . "/context/JsonLdProcessor.php");
require_once (__DIR__ . "/context/Keywords.php");
require_once (__DIR__ . "/context/TermDefinition.php");

require_once (__DIR__ . "/presentation/common/TypeMap.php");
require_once (__DIR__ . "/presentation/common/model/AbstractIiifEntity.php");
require_once (__DIR__ . "/presentation/common/model/resources/IiifResourceInterface.php");
require_once (__DIR__ . "/presentation/common/model/resources/AnnotationContainerInterface.php");
require_once (__DIR__ . "/presentation/common/model/resources/AnnotationInterface.php");
require_once (__DIR__ . "/presentation/common/model/resources/CanvasInterface.php");
require_once (__DIR__ . "/presentation/common/model/resources/ContentResourceInterface.php");
require_once (__DIR__ . "/presentation/common/model/resources/ManifestInterface.php");
require_once (__DIR__ . "/presentation/common/model/resources/RangeInterface.php");
require_once (__DIR__ . "/presentation/common/vocabulary/Motivation.php");

require_once (__DIR__ . "/presentation/v2/model/constants/ViewingDirectionValues.php");
require_once (__DIR__ . "/presentation/v2/model/constants/ViewingHintValues.php");
require_once (__DIR__ . "/presentation/v2/model/properties/FormatTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/NavDateTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/StartCanvasTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/ViewingDirectionTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/WidthAndHeightTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/XYWHFragment.php");
require_once (__DIR__ . "/presentation/v2/model/resources/AbstractIiifResource2.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Annotation.php");
require_once (__DIR__ . "/presentation/v2/model/resources/AnnotationList.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Canvas.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Collection.php");
require_once (__DIR__ . "/presentation/v2/model/resources/ContentResource.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Layer.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Manifest.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Range.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Sequence.php");
require_once (__DIR__ . "/presentation/v2/model/vocabulary/Names.php");
require_once (__DIR__ . "/presentation/v2/model/vocabulary/Types.php");

require_once (__DIR__ . "/presentation/v3/model/constants/BehaviorValues.php");

require_once (__DIR__ . "/presentation/v3/model/resources/AbstractIiifResource3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/Annotation3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/AnnotationCollection3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/AnnotationPage3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/Canvas3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/Collection3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/ContentResource3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/Manifest3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/Range3.php");
require_once (__DIR__ . "/presentation/v3/model/resources/SpecificResource3.php");

require_once (__DIR__ . "/services/Service.php");
require_once (__DIR__ . "/services/AbstractImageService.php");
require_once (__DIR__ . "/services/ImageInformation1.php");
require_once (__DIR__ . "/services/ImageInformation2.php");
require_once (__DIR__ . "/services/ImageInformation3.php");
require_once (__DIR__ . "/services/PhysicalDimensions.php");
require_once (__DIR__ . "/services/Profile.php");

require_once (__DIR__ . "/tools/IiifHelper.php");
require_once (__DIR__ . "/tools/Options.php");
require_once (__DIR__ . "/tools/UrlReaderInterface.php");

// TODO remove
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/AccessHelper.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/JSONPath.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/JSONPathException.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/JSONPathLexer.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/JSONPathToken.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/AbstractFilter.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/IndexesFilter.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/IndexFilter.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/QueryMatchFilter.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/QueryResultFilter.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/RecursiveFilter.php");
require_once(__DIR__ . "/../../vendor/flow/jsonpath/src/Flow/JSONPath/Filters/SliceFilter.php");
