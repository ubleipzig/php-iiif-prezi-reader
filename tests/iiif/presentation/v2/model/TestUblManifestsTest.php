<?php

use iiif\AbstractIiifTest;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Sequence;
use iiif\tools\IiifHelper;

class TestUblManifestsTest extends AbstractIiifTest
{
    const MANIFEST_EXAMPLES = array('v2/manifest-0000006761.json', 'v2/manifest-0000000720.json');
    
    public function testUBLManifest()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $manifestAsJson=self::getFile($manifestFile);
            Manifest::loadIiifResource($manifestAsJson, $manifestFile." did not load");
        }
    }
    
    public function testUBLIiifHelperIiifResourceFromJsonString()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $documentAsJson=self::getFile($manifestFile);
            $resource=IiifHelper::loadIiifResource($documentAsJson);
            self::assertInstanceOf(Manifest::class, $resource, 'Not a manifest: '.$manifestFile);
            self::assertNotNull($resource->getSequences(), 'No sequences found: '.$manifestFile);
            self::assertNotEmpty($resource->getSequences(), 'Sequences empty: '.$manifestFile);
            self::assertNotNull($resource->getSequences()[0], 'First sequence not found: '.$manifestFile);
            self::assertInstanceOf(Sequence::class, $resource->getSequences()[0], 'First sequence is not a sequence: '.$manifestFile);
        }
    }
}

