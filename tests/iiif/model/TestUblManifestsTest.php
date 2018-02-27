<?php

use PHPUnit\Framework\TestCase;
use iiif\model\resources\Manifest;
use iiif\model\helper\IiifReader;
use iiif\model\resources\Sequence;

/**
 * TestUblManifests test case.
 */
class UblManifestsTest extends TestCase
{
    const MANIFEST_EXAMPLES = array('manifest-0000006761.json', 'manifest-0000000720.json');
    
    public function testUBLManifest()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $manifestAsJson=file_get_contents(__DIR__.'/../../resources/'.$manifestFile);
            Manifest::fromJson($manifestAsJson, $manifestFile." did not load");
        }
    }
    
    public function testUBLResourceType()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $documentAsJson=file_get_contents(__DIR__.'/../../resources/'.$manifestFile);
            $classForDocument = IiifReader::getResourceClassForString($documentAsJson);
            self::assertEquals(Manifest::class, $classForDocument, 'Wrong resource class for '.$manifestFile);
            $classForDocument::fromJson($documentAsJson, 'Loading '.$manifestFile.'by class did not work');
        }
    }
    
    public function testUBLIiifHelperIiifResourceFromJsonString()
    {
        foreach (self::MANIFEST_EXAMPLES as $manifestFile)
        {
            $documentAsJson=file_get_contents(__DIR__.'/../../resources/'.$manifestFile);
            $resource=IiifReader::getIiifResourceFromJsonString($documentAsJson);
            self::assertInstanceOf(Manifest::class, $resource, 'Not a manifest: '.$manifestFile);
            self::assertNotNull($resource->getSequences(), 'No sequences found: '.$manifestFile);
            self::assertNotEmpty($resource->getSequences(), 'Sequences empty: '.$manifestFile);
            self::assertNotNull($resource->getSequences()[0], 'First sequence not found: '.$manifestFile);
            self::assertInstanceOf(Sequence::class, $resource->getSequences()[0], 'First sequence is not a sequence: '.$manifestFile);
        }
    }
}

