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
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest2;
use Ubl\Iiif\Tools\Options;
use Ubl\Iiif\DummyUrlReader;

/**
 * IiifHelper test case.
 */
class IiifHelperTest extends AbstractIiifTest {

    protected $example = "http://www.example.org/";

    
    /**
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::tearDown()
     */
    protected function tearDown(): void
    {
        IiifHelper::setUrlReader(null);
    }

    /**
     * Tests IiifHelper::loadIiifResource()
     */
    public function testLoadIiifResource() {
        $notNullResult = IiifHelper::loadIiifResource(self::getFile("v2/empty-manifest.json"));
        self::assertNotNull($notNullResult);
        self::assertInstanceOf(Manifest2::class, $notNullResult);
        
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
        self::assertInstanceOf(Manifest2::class, $iiif);
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

