<?php
use iiif\model\resources\MockIiifResource;

/**
 * AbstractIiifResource test case.
 */
class AbstractIiifResourceTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var MockIiifResource
     */
    private $abstractIiifResource;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated AbstractIiifResourceTest::setUp()
        
        $this->abstractIiifResource = new MockIiifResource();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated AbstractIiifResourceTest::tearDown()
        $this->abstractIiifResource = null;
        
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
     * Tests AbstractIiifResource::fromJson()
     */
    public function testFromJson()
    {
        // TODO Auto-generated AbstractIiifResourceTest::testFromJson()
        $this->markTestIncomplete("fromJson test not implemented");
        
        MockIiifResource::fromJson(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getDefaultLabel()
     */
    public function testGetDefaultLabel()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetDefaultLabel()
        $this->markTestIncomplete("getDefaultLabel test not implemented");
        
        $this->abstractIiifResource->getDefaultLabel(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->isReference()
     */
    public function testIsReference()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testIsReference()
        $this->markTestIncomplete("isReference test not implemented");
        
        $this->abstractIiifResource->isReference(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getId()
     */
    public function testGetId()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetId()
        $this->markTestIncomplete("getId test not implemented");
        
        $this->abstractIiifResource->getId(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getService()
     */
    public function testGetService()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetService()
        $this->markTestIncomplete("getService test not implemented");
        
        $this->abstractIiifResource->getService(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getThumbnail()
     */
    public function testGetThumbnail()
    {
        // TODO Auto-generated AbstractIiifResourceTest->testGetThumbnail()
        $this->markTestIncomplete("getThumbnail test not implemented");
        
        $this->abstractIiifResource->getThumbnail(/* parameters */);
    }

    /**
     * Tests AbstractIiifResource->getMetadateForLabel()
     */
    public function testGetMetadataForLabel()
    {
        $this->abstractIiifResource->setMetadata(null);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Hopfully no error if metadata is set to null");
        self::assertNull($metadataValue);
        
        $metadataString = '[]';
        $metadata = json_decode($metadataString, true);
        $this->abstractIiifResource->setMetadata($metadata);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Hopfully no error for empty metadata");
        self::assertNull($metadataValue);
        
        $metadataString = '[{"label": "My label", "value": "My value"}]';
        $metadata = json_decode($metadataString, true);
        $this->abstractIiifResource->setMetadata($metadata);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("Not my label");
        self::assertNull($metadataValue);
        $metadataValue = $this->abstractIiifResource->getMetadataForLabel("My label");
        self::assertEquals("My value", $metadataValue);

        $metadataString = '[{"label": [{"@value": "My label", "@language": "en"}, {"@value": "Meine Beschriftung", "@language": "de"}], "value": [{"@value": "My value", "@language": "en"}, {"@value": "Mein Wert", "@language": "de"}]}]';
        
        
        // TODO Auto-generated AbstractIiifResourceTest->testGetMetadateForLabel()
        $this->markTestIncomplete("getMetadateForLabel test not implemented");
        
        $this->abstractIiifResource->getMetadataForLabel(/* parameters */);
    }
}

