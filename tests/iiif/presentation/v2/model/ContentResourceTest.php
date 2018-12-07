<?php
use iiif\AbstractIiifTest;
use iiif\presentation\v2\model\resources\ContentResource;

/**
 * ContentResource test case.
 */
class ContentResourceTest extends AbstractIiifTest {

    /**
     *
     * @var ContentResource
     */
    private $contentResource;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        
        // TODO Auto-generated ContentResourceTest::setUp()
        
        $this->contentResource = new ContentResource(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        // TODO Auto-generated ContentResourceTest::tearDown()
        $this->contentResource = null;
        
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct() {
        // TODO Auto-generated constructor
    }

    /**
     * Tests ContentResource->getFormat()
     */
    public function testGetFormat() {
        // TODO Auto-generated ContentResourceTest->testGetFormat()
        $this->markTestIncomplete("getFormat test not implemented");
        
        $this->contentResource->getFormat(/* parameters */);
    }

    /**
     * Tests ContentResource->getChars()
     */
    public function testGetChars() {
        // TODO Auto-generated ContentResourceTest->testGetChars()
        $this->markTestIncomplete("getChars test not implemented");
        
        $this->contentResource->getChars(/* parameters */);
    }

    /**
     * Tests ContentResource->getImageUrl()
     */
    public function testGetImageUrl() {
        // TODO Auto-generated ContentResourceTest->testGetImageUrl()
        $this->markTestIncomplete("getImageUrl test not implemented");
        
        $this->contentResource->getImageUrl(/* parameters */);
    }
}

