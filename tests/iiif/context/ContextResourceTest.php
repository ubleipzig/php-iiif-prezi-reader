<?php

use PHPUnit\Framework\TestCase;

/**
 *  Test case to ensure contexts are up to date
 */
class ContextResourceTest extends TestCase {
    
    /**
     * @var array Local conbtext
     */
    const CONTEXTS = [
        "http://www.w3.org/ns/anno.jsonld" => "annotation/annotation-context.json",
        "http://iiif.io/api/auth/1/context.json" => "iiif/auth-context-1.json",
        "http://iiif.io/api/image/1/context.json" => "iiif/image-context-1.json",
        "http://iiif.io/api/image/2/context.json" => "iiif/image-context-2.json",
        "http://iiif.io/api/image/3/context.json" => "iiif/image-context-3.json",
        "http://iiif.io/api/presentation/3/combined-context.json" => "iiif/presentation-combined-context-3.json",
        "http://iiif.io/api/presentation/1/context.json" => "iiif/presentation-context-1.json",
        "http://iiif.io/api/presentation/2/context.json" => "iiif/presentation-context-2.json",
        "http://iiif.io/api/presentation/3/context.json" => "iiif/presentation-context-3.json",
        "http://iiif.io/api/search/1/context.json" => "iiif/search-context-1.json"
    ];

    /**
     * Ensure that the JSON-LD contexts that are provided with this library are the same as the online resources.
     */
    public function testLocalResoucesEqualRemoteResources() {
        foreach (self::CONTEXTS as $url => $localFilename) {
            $remoteContent = file_get_contents($url);
            $localContent = file_get_contents(__DIR__."/../../../resources/contexts/".$localFilename);
            self::assertEquals($remoteContent, $localContent, "Local version of ".$url." different from remote version.");
        }
    }
    
}

