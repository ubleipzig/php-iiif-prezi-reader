<?php
use iiif\model\AbstractIiifTest;
use iiif\model\resources\Manifest;
use iiif\model\resources\Sequence;
use iiif\model\constants\ViewingDirectionValues;

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
        $this->manifest = Manifest::fromJson($this->json);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ManifestTest::tearDown()
        $this->manifest = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests Manifest::fromArray()
     */
    public function testFromArray()
    {
        // TODO Auto-generated ManifestTest::testFromArray()
        $this->markTestIncomplete("fromArray test not implemented");
        
        Manifest::fromArray(/* parameters */);
    }

    /**
     * Tests Manifest->getSequences()
     */
    public function testGetSequences()
    {
        // TODO Auto-generated ManifestTest->testGetSequences()
        $this->markTestIncomplete("getSequences test not implemented");
        
        $this->manifest->getSequences(/* parameters */);
    }

    /**
     * Tests Manifest->getStructures()
     */
    public function testGetStructures()
    {
        // TODO Auto-generated ManifestTest->testGetStructures()
        $this->markTestIncomplete("getStructures test not implemented");
        
        $this->manifest->getStructures(/* parameters */);
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
        $manifest = Manifest::fromJson($json);
        
    }
}

