<?php

use iiif\presentation\v2\model\AbstractIiifTest;
use iiif\presentation\v2\model\helper\IiifReader;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Sequence;

class TestUblManifestsTest extends AbstractIiifTest
{
    const MANIFEST_EXAMPLES = array('manifest-0000006761.json', 'manifest-0000000720.json');
    
    public function testUBLManifest()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $manifestAsJson=self::getJson($manifestFile);
            Manifest::fromJson($manifestAsJson, $manifestFile." did not load");
        }
    }
    
    public function testUBLManifestGetResourceClassForString()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $documentAsJson=self::getJson($manifestFile);
            $classForDocument = IiifReader::getResourceClassForString($documentAsJson);
            self::assertEquals(Manifest::class, $classForDocument, 'Wrong resource class for '.$manifestFile);
            $classForDocument::fromJson($documentAsJson, 'Loading '.$manifestFile.'by class did not work');
        }
    }
    
    public function testUBLIiifHelperIiifResourceFromJsonString()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $documentAsJson=self::getJson($manifestFile);
            $resource=IiifReader::getIiifResourceFromJsonString($documentAsJson);
            self::assertInstanceOf(Manifest::class, $resource, 'Not a manifest: '.$manifestFile);
            self::assertNotNull($resource->getSequences(), 'No sequences found: '.$manifestFile);
            self::assertNotEmpty($resource->getSequences(), 'Sequences empty: '.$manifestFile);
            self::assertNotNull($resource->getSequences()[0], 'First sequence not found: '.$manifestFile);
            self::assertInstanceOf(Sequence::class, $resource->getSequences()[0], 'First sequence is not a sequence: '.$manifestFile);
        }
    }
}

