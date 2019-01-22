<?php
namespace iiif\tools;

class Options {
    
    /**
     * @var UrlReaderInterface
     */
    protected static $urlReader;
    
    /**
     * @var int
     */
    protected static $maxThumbnailWidth = null;
    
    /**
     * @var int
     */
    protected static $maxThumbnailHeight = null;

    /**
     * @return \iiif\tools\UrlReaderInterface
     */
    public static function getUrlReader() {
        return Options::$urlReader;
    }

    /**
     * @return number
     */
    public static function getMaxThumbnailWidth() {
        return self::$maxThumbnailWidth == null ? 100 : self::$maxThumbnailWidth;
    }

    /**
     * @return number
     */
    public static function getMaxThumbnailHeight() {
        return self::$maxThumbnailHeight == null ? 100 : self::$maxThumbnailHeight;
    }

    /**
     * @param \iiif\tools\UrlReaderInterface $urlReader
     */
    public static function setUrlReader($urlReader) {
        Options::$urlReader = $urlReader;
    }

    /**
     * @param number $maxThumbnailWidth
     */
    public static function setMaxThumbnailWidth($maxThumbnailWidth) {
        Options::$maxThumbnailWidth = $maxThumbnailWidth;
    }

    /**
     * @param number $maxThumbnailHeight
     */
    public static function setMaxThumbnailHeight($maxThumbnailHeight) {
        Options::$maxThumbnailHeight = $maxThumbnailHeight;
    }

}

