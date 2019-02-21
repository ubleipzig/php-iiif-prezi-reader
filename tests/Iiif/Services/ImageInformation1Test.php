<?php
/*
 * Copyright (C) 2019 Leipzig University Library <info@ub.uni-leipzig.de>
 * 
 * This file is part of the php-iiif-prezi-reader.
 * 
 * php-iiif-prezi-reader is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

use Ubl\Iiif\AbstractIiifTest;
use Ubl\Iiif\Services\ImageInformation1;
use Ubl\Iiif\Services\Profile;
use Ubl\Iiif\Tools\IiifHelper;

/**
 * ImageInformation1 test case.
 */
class ImageInformation1Test extends AbstractIiifTest {

    /**
     * Tests ImageInformation1->getFormats()
     */
    public function testGetFormats() {
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-01.json"));
        /* @var $service ImageInformation1 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEmpty($service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(2, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("png", $service->getFormats());

        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(1, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(2, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("png", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(2, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("png", $service->getFormats());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getFormats());
        self::assertEquals(4, count($service->getFormats()));
        self::assertContains("jpg", $service->getFormats());
        self::assertContains("png", $service->getFormats());
        self::assertContains("tif", $service->getFormats());
        self::assertContains("pdf", $service->getFormats());
    }
    
    /**
     * Tests ImageInformation1->getQualities()
     */
    public function testGetQualities() {
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-01.json"));
        /* @var $service ImageInformation1 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(1, count($service->getQualities()));
        self::assertContains(Profile::NATIVE, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(2, count($service->getQualities()));
        self::assertContains(Profile::NATIVE, $service->getQualities());
        self::assertContains(Profile::GREY, $service->getQualities());

        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(1, count($service->getQualities()));
        self::assertContains(Profile::NATIVE, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(2, count($service->getQualities()));
        self::assertContains(Profile::NATIVE, $service->getQualities());
        self::assertContains(Profile::GREY, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(4, count($service->getQualities()));
        self::assertContains(Profile::NATIVE, $service->getQualities());
        self::assertContains(Profile::GREY, $service->getQualities());
        self::assertContains(Profile::COLOR, $service->getQualities());
        self::assertContains(Profile::BITONAL, $service->getQualities());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getQualities());
        self::assertEquals(4, count($service->getQualities()));
        self::assertContains(Profile::NATIVE, $service->getQualities());
        self::assertContains(Profile::GREY, $service->getQualities());
        self::assertContains(Profile::COLOR, $service->getQualities());
        self::assertContains(Profile::BITONAL, $service->getQualities());
    }
    
    /**
     * Tests ImageInformation1->getSupports()
     */
    public function testGetSupports() {
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-01.json"));
        /* @var $service ImageInformation1 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEmpty($service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEmpty($service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(5, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(5, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(8, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::REGION_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_CONFINED_WH, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertNotEmpty($service->getSupports());
        self::assertEquals(8, count($service->getSupports()));
        self::assertContains(Profile::REGION_BY_PX, $service->getSupports());
        self::assertContains(Profile::REGION_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_W, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_H, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_PCT, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_WH, $service->getSupports());
        self::assertContains(Profile::SIZE_BY_CONFINED_WH, $service->getSupports());
        self::assertContains(Profile::ROTATION_BY_90S, $service->getSupports());
    }
    
    /**
     * Tests ImageInformation1->isFeatureSupported()
     */
    public function testIsFeatureSupported() {

        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-01.json"));
        /* @var $service ImageInformation1 */
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
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
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
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
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertTrue($service->isFeatureSupported(Profile::REGION_BY_PX));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::REGION_SQUARE));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_W));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_H));
        self::assertTrue($service->isFeatureSupported(Profile::SIZE_BY_PCT));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_WH));
        self::assertFalse($service->isFeatureSupported(Profile::SIZE_BY_CONFINED_WH));
        self::assertTrue($service->isFeatureSupported(Profile::ROTATION_BY_90S));
        self::assertFalse($service->isFeatureSupported(Profile::ROTATION_ARBITRARY));
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
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
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
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
    }
    
    /**
     * Tests ImageInformation1->getImageUrl()
     */
    public function testGetImageUrl() {
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertEquals("http://example.com/image/05/full/full/0/native.jpg", $service->getImageUrl());
        self::assertEquals("http://example.com/image/05/100,100,200,200/200,/90/bitonal.png", $service->getImageUrl("100,100,200,200", "200,","90","bitonal","png"));
    }
    
    public function testGetId() {
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://example.com/image/01", $service->getId());
    
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://example.com/image/02", $service->getId());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://example.com/image/03", $service->getId());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://example.com/image/04", $service->getId());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://example.com/image/05", $service->getId());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://example.com/image/06", $service->getId());
    }

    public function testGetProfile() {
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://library.stanford.edu/iiif/image-api/compliance.html#level0", $service->getProfile());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level0-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://library.stanford.edu/iiif/image-api/compliance.html#level0", $service->getProfile());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://library.stanford.edu/iiif/image-api/compliance.html#level1", $service->getProfile());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level1-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://library.stanford.edu/iiif/image-api/compliance.html#level1", $service->getProfile());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-01.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://library.stanford.edu/iiif/image-api/compliance.html#level2", $service->getProfile());
        
        $service = IiifHelper::loadIiifResource(static::getFile("services/image1-level2-02.json"));
        self::assertNotNull($service);
        self::assertInstanceOf(ImageInformation1::class, $service);
        self::assertEquals("http://library.stanford.edu/iiif/image-api/compliance.html#level2", $service->getProfile());
    }
    
}

