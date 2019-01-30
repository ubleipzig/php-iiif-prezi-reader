<?php
use iiif\AbstractIiifTest;
use iiif\tools\IiifHelper;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\tools\Options;
use iiif\DummyUrlReader;

/**
 * IiifHelper test case.
 */
class IiifHelperTest extends AbstractIiifTest {

    protected $example = "http://www.example.org/";

    
    /**
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::tearDown()
     */
    protected function tearDown() {
        IiifHelper::setUrlReader(null);
    }

    /**
     * Tests IiifHelper::loadIiifResource()
     */
    public function testLoadIiifResource() {
        $notNullResult = IiifHelper::loadIiifResource(self::getFile("v2/empty-manifest.json"));
        self::assertNotNull($notNullResult);
        self::assertInstanceOf(Manifest::class, $notNullResult);
        
        $nullResult = IiifHelper::loadIiifResource(null);
        self::assertNull($nullResult);
    }

    /**
     * Tests IiifHelper::getRemoteContent()
     */
    public function testGetRemoteContent() {
        $example = IiifHelper::getRemoteContent($this->example);
        self::assertStringContainsString("Example Domain", $example);
        
        IiifHelper::setUrlReader(new DummyUrlReader());
        $dummy = IiifHelper::getRemoteContent($this->example);
        self::assertStringNotContainsString("Example Domain", $dummy);
        self::assertStringContainsString("@context", $dummy);
    }

    /**
     * Tests IiifHelper::setUrlReader()
     */
    public function testSetUrlReader() {
        $example = IiifHelper::getRemoteContent($this->example);
        self::assertStringContainsString("Example Domain", $example);
        $iiif = IiifHelper::loadIiifResource($this->example);
        self::assertNull($iiif);
        
        IiifHelper::setUrlReader(new DummyUrlReader());
        $dummy = IiifHelper::getRemoteContent($this->example);
        self::assertStringNotContainsString("Example Domain", $dummy);
        self::assertStringContainsString("@context", $dummy);
        
        $iiif = IiifHelper::loadIiifResource($this->example);
        self::assertNotNull($iiif);
        self::assertInstanceOf(Manifest::class, $iiif);
        self::assertEquals($this->example, $iiif->getId());
        
        IiifHelper::setUrlReader(null);
        $example = IiifHelper::getRemoteContent($this->example);
        self::assertStringContainsString("Example Domain", $example);
        $iiif = IiifHelper::loadIiifResource($this->example);
        self::assertNull($iiif);
    }

    /**
     * Tests IiifHelper::setMaxThumbnailWidth()
     */
    public function testSetMaxThumbnailWidth() {
        self::assertEquals(100, Options::getMaxThumbnailWidth());
        IiifHelper::setMaxThumbnailWidth(1234);
        self::assertEquals(1234, Options::getMaxThumbnailWidth());
        IiifHelper::setMaxThumbnailWidth(4321);
        self::assertEquals(4321, Options::getMaxThumbnailWidth());
        IiifHelper::setMaxThumbnailWidth(null);
        self::assertEquals(100, Options::getMaxThumbnailWidth());
    }

    /**
     * Tests IiifHelper::setMaxThumbnailHeight()
     */
    public function testSetMaxThumbnailHeight() {
        self::assertEquals(100, Options::getMaxThumbnailHeight());
        IiifHelper::setMaxThumbnailHeight(1234);
        self::assertEquals(1234, Options::getMaxThumbnailHeight());
        IiifHelper::setMaxThumbnailHeight(4321);
        self::assertEquals(4321, Options::getMaxThumbnailHeight());
        IiifHelper::setMaxThumbnailHeight(null);
        self::assertEquals(100, Options::getMaxThumbnailHeight());
    }
}

