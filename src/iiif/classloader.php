<?php

// Use this file if you don't want to or can't rely on composer or other autoloaders
require_once (__DIR__ . "/presentation/IiifHelper.php");

require_once (__DIR__ . "/presentation/common/model/AbstractIiifEntity.php");

require_once (__DIR__ . "/presentation/v2/model/constants/Profile.php");
require_once (__DIR__ . "/presentation/v2/model/constants/ViewingDirectionValues.php");
require_once (__DIR__ . "/presentation/v2/model/helper/IiifReader.php");
require_once (__DIR__ . "/presentation/v2/model/properties/FormatTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/NavDateTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/StartCanvasTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/ViewingDirectionTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/WidthAndHeightTrait.php");
require_once (__DIR__ . "/presentation/v2/model/properties/XYWHFragment.php");
require_once (__DIR__ . "/presentation/v2/model/resources/AbstractIiifResource.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Annotation.php");
require_once (__DIR__ . "/presentation/v2/model/resources/AnnotationList.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Canvas.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Collection.php");
require_once (__DIR__ . "/presentation/v2/model/resources/ContentResource.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Layer.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Manifest.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Range.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Sequence.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Service.php");
require_once (__DIR__ . "/presentation/v2/model/resources/Thumbnail.php");
require_once (__DIR__ . "/presentation/v2/model/vocabulary/MiscNames.php");
require_once (__DIR__ . "/presentation/v2/model/vocabulary/Motivation.php");
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

require_once (__DIR__ . "/services/AbstractImageService.php");
require_once (__DIR__ . "/services/ImageInformation1.php");
require_once (__DIR__ . "/services/ImageInformation2.php");
require_once (__DIR__ . "/services/ImageInformation3.php");
require_once (__DIR__ . "/services/PhysicalDimensions.php");
require_once (__DIR__ . "/services/Service.php");

