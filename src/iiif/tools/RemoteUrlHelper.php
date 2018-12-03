<?php
namespace iiif\tools;

class RemoteUrlHelper {
    
    /**
     * @var UrlReaderInterface
     */
    protected static $urlReader;

    /**
     * @param \iiif\tools\UrlReaderInterface $urlReader
     */
    public static function setUrlReader(UrlReaderInterface $urlReader) {
        self::$urlReader = $urlReader;
    }
    
    public static function getContent(string $url) {
        if (isset(self::$urlReader)) {
            return self::$urlReader->getContent($url);
        }
        return file_get_contents($url);
    }
    
}

