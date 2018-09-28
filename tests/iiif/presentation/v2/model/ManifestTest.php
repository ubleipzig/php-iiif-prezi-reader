<?php
use iiif\presentation\v2\model\AbstractIiifTest;
use iiif\presentation\v2\model\constants\ViewingDirectionValues;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Range;
use iiif\presentation\v2\model\resources\Sequence;

/**
 * Manifest test case.
 */
class ManifestTest extends AbstractIiifTest
{

    /**
     *
     * @var Manifest
     */
    private $manifest;
    private $json;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->json = parent::getJson('manifest-example.json');
        $this->manifest = Manifest::loadIiifResource($this->json);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->manifest = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
    }

    /**
     * Tests Manifest::fromArray()
     */
    public function testFromArray()
    {
        $jsonAsArray = json_decode($this->json, true);
        $manifest = Manifest::loadIiifResource($jsonAsArray);
        self::assertNotNull($manifest);
        self::assertEquals("http://example.org/iiif/book1/manifest", $manifest->getId());
    }

    /**
     * Tests Manifest->getSequences()
     */
    public function testGetSequences()
    {
        $sequences = $this->manifest->getSequences();
        self::assertNotNull($sequences);
        self::assertTrue(is_array($sequences));
        self::assertEquals(1, sizeOf($sequences));
        self::assertNotNull($sequences[0]);
        self::assertInstanceOf(Sequence::class, $sequences[0]);
        self::assertEquals("http://example.org/iiif/book1/sequence/normal", $sequences[0]->getId());
    }

    /**
     * Tests Manifest->getStructures()
     */
    public function testGetStructures()
    {
        $structures = $this->manifest->getStructures();
        self::assertNotNull($structures);
        self::assertTrue(is_array($structures));
        self::assertEquals(1, sizeOf($structures));
        self::assertNotNull($structures[0]);
        self::assertInstanceOf(Range::class, $structures[0]);
    }

    /**
     * Tests Manifest->getContainedResourceById()
     */
    public function testGetContainedResourceById()
    {
        $containedSequence = $this->manifest->getContainedResourceById("http://example.org/iiif/book1/sequence/normal");
        self::assertNotNull($containedSequence);
        
        $defaultSequence = $this->manifest->getSequences()[0];
        self::assertNotNull($defaultSequence);
        /* @var $defaultSequence Sequence */
        
        self::assertEquals($defaultSequence->getId(), $containedSequence->getId());
        self::assertEquals(ViewingDirectionValues::LEFT_TO_RIGHT, $defaultSequence->getViewingDirection());
        self::assertEquals(ViewingDirectionValues::LEFT_TO_RIGHT, $containedSequence->getViewingDirection());
        
        $defaultSequence->setViewingDirection(ViewingDirectionValues::BOTTOM_TO_TOP);
        self::assertEquals(ViewingDirectionValues::BOTTOM_TO_TOP, $containedSequence->getViewingDirection());
        
        
        $containedManifest = $this->manifest->getContainedResourceById($this->manifest->getId());
        self::assertNotNull($containedManifest);
        
        self::assertEquals($this->manifest->getId(), $containedManifest->getId());
        self::assertEquals(null, $this->manifest->getViewingDirection());
        self::assertEquals(null, $containedManifest->getViewingDirection());
        
        $this->manifest->setViewingDirection(ViewingDirectionValues::BOTTOM_TO_TOP);
        self::assertEquals(ViewingDirectionValues::BOTTOM_TO_TOP, $containedManifest->getViewingDirection());
        

        $range1 = $this->manifest->getStructures()[0];
        self::assertNotNull($range1);
        $containedRange = $this->manifest->getContainedResourceById("http://example.org/iiif/book1/range/r1");
        self::assertNotNull($containedRange);
        
        self::assertEquals($range1->getId(), $containedRange->getId());
        self::assertEquals(null, $range1->getViewingDirection());
        self::assertEquals(null, $containedRange->getViewingDirection());
        
        $range1->setViewingDirection(ViewingDirectionValues::BOTTOM_TO_TOP);
        self::assertEquals(ViewingDirectionValues::BOTTOM_TO_TOP, $containedRange->getViewingDirection());
    }
    
    public function testGetNavDateAsDateTime()
    {
        self::assertEquals('1856-01-01T00:00:00Z', $this->manifest->getNavDate());
        
        $navDate = $this->manifest->getNavDateAsDateTime();
        self::assertInstanceOf(DateTime::class, $navDate);
        self::assertEquals('1856', $navDate->format('Y'));
        self::assertEquals('1', $navDate->format('m'));
        self::assertEquals('1', $navDate->format('d'));
    }
    
    public function testEmptyManifest()
    {
        $json = parent::getJson('empty-manifest.json');
        $manifest = Manifest::loadIiifResource($json);
        
    }
}

