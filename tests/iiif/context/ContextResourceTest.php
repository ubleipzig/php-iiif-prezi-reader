<?php

/**
 *  test case.
 */
class ContextResourceTest extends PHPUnit_Framework_TestCase {
    
    const CONTEXTS = [
        "http://www.w3.org/ns/anno.jsonld" => "annotation-context.json",
        "http://iiif.io/api/auth/1/context.json" => "auth-context-1.json",
        "http://iiif.io/api/image/1/context.json" => "image-context-1.json",
        "http://iiif.io/api/image/2/context.json" => "image-context-2.json",
        "http://iiif.io/api/image/3/context.json" => "image-context-3.json",
        "http://iiif.io/api/presentation/3/combined-context.json" => "presentation-combined-context-3.json",
        "http://iiif.io/api/presentation/1/context.json" => "presentation-context-1.json",
        "http://iiif.io/api/presentation/2/context.json" => "presentation-context-2.json",
        "http://iiif.io/api/presentation/3/context.json" => "presentation-context-3.json",
        "http://iiif.io/api/search/1/context.json" => "search-context-1.json"
    ];

    public function testLocalResoucesEqualRemoteResources() {
        foreach (self::CONTEXTS as $url => $localFilename) {
            $remoteContent = file_get_contents($url);
            $localContent = file_get_contents(__DIR__."/../../../resources/contexts/".$localFilename);
            self::assertEquals($remoteContent, $localContent, "Local version of ".$url." different from remote version.");
        }
    }
    
}

