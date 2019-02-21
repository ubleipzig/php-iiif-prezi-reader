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

// Use this file if you don't want to or can't rely on composer or other autoloaders

require_once (__DIR__ . "/Context/IRI.php");
require_once (__DIR__ . "/Context/JsonLdContext.php");
require_once (__DIR__ . "/Context/JsonLdHelper.php");
require_once (__DIR__ . "/Context/JsonLdProcessor.php");
require_once (__DIR__ . "/Context/Keywords.php");
require_once (__DIR__ . "/Context/TermDefinition.php");

require_once (__DIR__ . "/Presentation/Common/TypeMap.php");
require_once (__DIR__ . "/Presentation/Common/Model/AbstractIiifEntity.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/IiifResourceInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/AbstractIiifResource.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/AnnotationContainerInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/AnnotationInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/CanvasInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/CollectionInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/ContentResourceInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/ManifestInterface.php");
require_once (__DIR__ . "/Presentation/Common/Model/Resources/RangeInterface.php");
require_once (__DIR__ . "/Presentation/Common/Vocabulary/Motivation.php");

require_once (__DIR__ . "/Presentation/V1/Model/Resources/AbstractIiifResource1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/AbstractDescribableResource1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/Annotation1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/AnnotationList1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/Canvas1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/ContentResource1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/Layer1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/Manifest1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/Range1.php");
require_once (__DIR__ . "/Presentation/V1/Model/Resources/Sequence1.php");

require_once (__DIR__ . "/Presentation/V2/Model/Constants/ViewingDirectionValues.php");
require_once (__DIR__ . "/Presentation/V2/Model/Constants/ViewingHintValues.php");
require_once (__DIR__ . "/Presentation/V2/Model/Properties/FormatTrait.php");
require_once (__DIR__ . "/Presentation/V2/Model/Properties/NavDateTrait.php");
require_once (__DIR__ . "/Presentation/V2/Model/Properties/StartCanvasTrait.php");
require_once (__DIR__ . "/Presentation/V2/Model/Properties/ViewingDirectionTrait.php");
require_once (__DIR__ . "/Presentation/V2/Model/Properties/WidthAndHeightTrait.php");
require_once (__DIR__ . "/Presentation/V2/Model/Properties/XYWHFragment.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/AbstractIiifResource2.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Annotation.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/AnnotationList.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Canvas.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Collection.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/ContentResource.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Layer.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Manifest.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Range.php");
require_once (__DIR__ . "/Presentation/V2/Model/Resources/Sequence.php");
require_once (__DIR__ . "/Presentation/V2/Model/Vocabulary/Terms.php");

require_once (__DIR__ . "/Presentation/V3/Model/Constants/BehaviorValues.php");

require_once (__DIR__ . "/Presentation/V3/Model/Properties/PlaceholderAndAccompanyingCanvasTrait.php");

require_once (__DIR__ . "/Presentation/V3/Model/Resources/AbstractIiifResource3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/Annotation3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/AnnotationCollection3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/AnnotationPage3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/Canvas3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/Collection3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/ContentResource3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/Manifest3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/Range3.php");
require_once (__DIR__ . "/Presentation/V3/Model/Resources/SpecificResource3.php");

require_once (__DIR__ . "/Services/Service.php");
require_once (__DIR__ . "/Services/AbstractImageService.php");
require_once (__DIR__ . "/Services/ImageInformation1.php");
require_once (__DIR__ . "/Services/ImageInformation2.php");
require_once (__DIR__ . "/Services/ImageInformation3.php");
require_once (__DIR__ . "/Services/PhysicalDimensions.php");
require_once (__DIR__ . "/Services/Profile.php");

require_once (__DIR__ . "/Tools/IiifHelper.php");
require_once (__DIR__ . "/Tools/Options.php");
require_once (__DIR__ . "/Tools/UrlReaderInterface.php");

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
