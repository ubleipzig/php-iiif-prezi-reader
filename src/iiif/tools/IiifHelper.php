<?php
namespace iiif\tools;

use iiif\presentation\common\model\AbstractIiifEntity;

class IiifHelper {

    public static function loadIiifResource($resource) {
        return AbstractIiifEntity::loadIiifResource($resource);
    }

    public static function getRemoteContent($url) {
        if (Options::getUrlReader() != null) {
            return Options::getUrlReader()->getContent($url);
        }
        return file_get_contents($url);
    }
    
    public static function setUrlReader(UrlReaderInterface $urlReader = null) {
        Options::setUrlReader($urlReader);
    }
    
    public static function setMaxThumbnailWidth($maxThumbnailWidth) {
        Options::setMaxThumbnailWidth($maxThumbnailWidth);
    }

    public static function setMaxThumbnailHeight($maxThumbnailHeight) {
        Options::setMaxThumbnailHeight($maxThumbnailHeight);
    }
    
}

