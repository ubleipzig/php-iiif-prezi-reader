<?php
use iiif\model\AbstractIiifTest;
use iiif\model\resources\Manifest;

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
        // TODO Auto-generated ManifestTest->testGetContainedResourceById()
        $this->markTestIncomplete("getContainedResourceById test not implemented");
        
        $this->manifest->getContainedResourceById(/* parameters */);
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

