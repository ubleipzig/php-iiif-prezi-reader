<?php

use iiif\AbstractIiifTest;
use iiif\tools\IiifHelper;
use iiif\presentation\v1\model\resources\Manifest1;

class MetadataApiExampleTest extends AbstractIiifTest {
    
    public function testExampleManifest() {
        
        $manifest = IiifHelper::loadIiifResource(parent::getJson('v1/example-manifest-v1.json'));
        $manifest->getRootRanges();
        self::assertInstanceOf(Manifest1::class, $manifest);
        
        self::markTestIncomplete("implement!");
        
    }
    
}
