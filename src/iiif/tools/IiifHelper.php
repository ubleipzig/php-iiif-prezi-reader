<?php
namespace iiif\tools;

use iiif\presentation\common\model\AbstractIiifEntity;
use iiif\presentation\v2\model\resources\AbstractIiifResource2;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Range;
use iiif\presentation\v2\model\resources\Sequence;
use iiif\presentation\v3\model\resources\Manifest3;
use iiif\presentation\v3\model\resources\Range3;

class IiifHelper {

    public static function loadIiifResource($resource) {
        return AbstractIiifEntity::loadIiifResource($resource);
    }

    public static function getStartCanvasOrFirstCanvas(AbstractIiifEntity $resource) {
        if ($resource instanceof Manifest3 || $resource instanceof Range3) {
            if ($resource->getStart() != null)
                return $resource->getStart();
            if ($resource->getItems() != null && sizeof($resource->getItems()) > 0) {
                if ($resource instanceof Manifest3) {
                    return $resource->getItems()[0];
                }
                if ($resource instanceof Range3) {
                    return $resource->getAllCanvases()[0];
                }
            }
            return null;
        }
        if ($resource instanceof Manifest || $resource instanceof Range || $resource instanceof Sequence || $resource instanceof Canvas) {
            $resource->getStartCanvasOrFirstCanvas();
        }
    }
    
    public static function getRenderingUrlsForFormat($resource, $format, $useNestedResources = true) {
        if ($resource instanceof AbstractIiifResource2) {
            return $resource->getRenderingUrlsForFormat($format, $useNestedResources);
        }
    }
    
    public static function getRemoteContent($url) {
        if (isset(Options::$urlReader)) {
            return Options::$urlReader->getContent($url);
        }
        return file_get_contents($url);
    }
    
    public static function setUrlReader(UrlReaderInterface $urlReader) {
        Options::setUrlReader($urlReader);
    }
    
    public static function setMaxThumbnailWidth($maxThumbnailWidth) {
        Options::setMaxThumbnailWidth($maxThumbnailWidth);
    }

    public static function setMaxThumbnailHeight($maxThumbnailHeight) {
        Options::setMaxThumbnailHeight($maxThumbnailHeight);
    }
    
}

