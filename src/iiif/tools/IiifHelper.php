<?php
namespace iiif\tools;

use iiif\presentation\common\model\AbstractIiifEntity;

class IiifHelper {

    /**
     * 
     * @param string|array $resource IIIF resource. Can be an IRI, the JSON document as string
     * or a dictionary in form of a PHP associative array 
     * @return NULL|\iiif\presentation\common\model\AbstractIiifEntity An instance of the IIIF 
     */
    public static function loadIiifResource($resource) {
        return AbstractIiifEntity::loadIiifResource($resource);
    }


    /**
     * Retrieve a document by its URL either via file_get_contents() or via an instance of
     * UrlReaderInterface that has previously been set with IiifHelper::setUrlReader(). Primarily
     * use is library internal. 
     * @param string $url URL of the requested resource
     * @return null|string Text content of the requested resource or null
     */
    public static function getRemoteContent($url) {
        if (Options::getUrlReader() != null) {
            return Options::getUrlReader()->getContent($url);
        }
        return file_get_contents($url);
    }
    
    /**
     * Offers a way to use any framework logic to retrieve remote documents instead of just using
     * file_get_contents(). Implement UrlReaderInterface and call this method before loading any
     * remote IIIF documents
     * @param UrlReaderInterface $urlReader
     */
    public static function setUrlReader(UrlReaderInterface $urlReader = null) {
        Options::setUrlReader($urlReader);
    }
    
    /**
     * Set the maximum width for thumbails that offer a IIIF image service to generate thumbnails.
     * @param int $maxThumbnailWidth
     */
    public static function setMaxThumbnailWidth($maxThumbnailWidth) {
        Options::setMaxThumbnailWidth($maxThumbnailWidth);
    }

    /**
     * Set the maximum height for thumbails that offer a IIIF image service to generate thumbnails.
     * @param int $maxThumbnailHeight
     */
    public static function setMaxThumbnailHeight($maxThumbnailHeight) {
        Options::setMaxThumbnailHeight($maxThumbnailHeight);
    }
    
}

