<?php
use iiif\AbstractIiifTest;
use iiif\services\ImageInformation2;
use iiif\presentation\IiifHelper;
use iiif\services\Profile;

require_once 'iiif/services/ImageInformation2.php';

/**
 * ImageInformation2 test case.
 */
class ImageInformation2Test extends AbstractIiifTest {

    /**
     * Tests ImageInformation2->getFormats()
     */
    public function testGetFormats() {
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-01.json"));
        /* @var $service ImageInformation2 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(1, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(2, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("tif", $service->getFormats());

        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(1, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(2, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("pdf", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(2, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("png", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(4, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("png", $service->getFormats());
        self::assertContains("gif", $service->getFormats());
        self::assertContains("pdf", $service->getFormats());
    }
    
    /**
     * Tests ImageInformation2->getQualities()
     */
    public function testGetQualities() {
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-01.json"));
        /* @var $service ImageInformation2 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(1, count($service->getQualities()));
        self::assertContains(Profile::DEFAULT, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(3, count($service->getQualities()));
        self::assertContains(Profile::DEFAULT, $service->getQualities());
        self::assertContains(Profile::COLOR, $service->getQualities());
        self::assertContains(Profile::GRAY, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(1, count($service->getQualities()));
        self::assertContains(Profile::DEFAULT, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(3, count($service->getQualities()));
        self::assertContains(Profile::DEFAULT, $service->getQualities());
        self::assertContains(Profile::COLOR, $service->getQualities());
        self::assertContains(Profile::BITONAL, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(2, count($service->getQualities()));
        self::assertContains(Profile::DEFAULT, $service->getQualities());
        self::assertContains(Profile::BITONAL, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(3, count($service->getQualities()));
        self::assertContains(Profile::DEFAULT, $service->getQualities());
        self::assertContains(Profile::COLOR, $service->getQualities());
        self::assertContains(Profile::BITONAL, $service->getQualities());
    }
    
    /**
     * Tests ImageInformation2->getSupports()
     */
    public function testGetSupports() {
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-01.json"));
        /* @var $service ImageInformation2 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(1, count($service->getSupports()));
        self::assertContains(Profile::SIZE_BY_WH_LISTED, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(2, count($service->getSupports()));
        self::assertContains(Profile::SIZE_BY_WH_LISTED, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(8, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH_LISTED, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::BASE_URI_REDIRECT, $service->getSupports());
        self::assertContains(Profile::CORS, $service->getSupports());
        self::assertContains(Profile::JSONLD_MEDIA_TYPE, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(11, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH_LISTED, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        self::assertContains(Profile::MIRRORING, $service->getSupports());
        self::assertContains(Profile::BASE_URI_REDIRECT, $service->getSupports());
        self::assertContains(Profile::CORS, $service->getSupports());
        self::assertContains(Profile::JSONLD_MEDIA_TYPE, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(14, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::REGION_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH_LISTED, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_CONFINED_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_DISTORTED_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_FORCED_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        self::assertContains(Profile::BASE_URI_REDIRECT, $service->getSupports());
        self::assertContains(Profile::CORS, $service->getSupports());
        self::assertContains(Profile::JSONLD_MEDIA_TYPE, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(17, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::REGION_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH_LISTED, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_CONFINED_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_DISTORTED_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_FORCED_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_ABOVE_FULL, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        self::assertContains(Profile::ROTATION_ARBITRARY, $service->getSupports());
        self::assertContains(Profile::MIRRORING, $service->getSupports());
        self::assertContains(Profile::BASE_URI_REDIRECT, $service->getSupports());
        self::assertContains(Profile::CORS, $service->getSupports());
        self::assertContains(Profile::JSONLD_MEDIA_TYPE, $service->getSupports());
    }
    
    /**
     * Tests ImageInformation2->isFeatureSupported()
     */
    public function testIsFeatureSupported() {

        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-01.json"));
        /* @var $service ImageInformation2 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        self::assertFalse($service->isFeatureSupported(Profile::MIRRORING));
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        self::assertFalse($service->isFeatureSupported(Profile::MIRRORING));
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        self::assertFalse($service->isFeatureSupported(Profile::MIRRORING));
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertTrue($service->isFeatureSupported(Profile::MIRRORING));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        
        $service = IiifHelper::loadIiifResource(static::getJson("services/image2-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation2::class, $service);
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));

        $this->markTestIncomplete("isFeatureSupported test not implemented");
    }
    
    /**
     * Tests ImageInformation2->getImageUrl()
     */
    public function testGetImageUrl() {
        // TODO
        $this->markTestIncomplete("getImageUrl test not implemented");
    }
}

