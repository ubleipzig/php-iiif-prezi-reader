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
use Ubl\Iiif\Presentation\V2\Model\Constants\ViewingDirectionValues;
use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Range2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Sequence2;
use Ubl\Iiif\Tools\IiifHelper;

/**
 * Manifest2 test case.
 */
class Manifest2Test extends AbstractIiifTest
{

    /**
     *
     * @var Manifest2
     */
    private $manifest;
    private $json;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->json = parent::getFile('v2/manifest-example.json');
        $this->manifest = Manifest2::loadIiifResource($this->json);
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
     * Tests Manifest2::fromArray()
     */
    public function testFromArray()
    {
        $jsonAsArray = json_decode($this->json, true);
        $manifest = Manifest2::loadIiifResource($jsonAsArray);
        self::assertNotNull($manifest);
        self::assertEquals("http://example.org/iiif/book1/manifest", $manifest->getId());
    }

    /**
     * Tests Manifest2->getSequences()
     */
    public function testGetSequences()
    {
        $sequences = $this->manifest->getSequences();
        self::assertNotNull($sequences);
        self::assertTrue(is_array($sequences));
        self::assertEquals(1, sizeOf($sequences));
        self::assertNotNull($sequences[0]);
        self::assertInstanceOf(Sequence2::class, $sequences[0]);
        self::assertEquals("http://example.org/iiif/book1/sequence/normal", $sequences[0]->getId());
    }

    /**
     * Tests Manifest2->getStructures()
     */
    public function testGetStructures()
    {
        $structures = $this->manifest->getStructures();
        self::assertNotNull($structures);
        self::assertTrue(is_array($structures));
        self::assertEquals(1, sizeOf($structures));
        self::assertNotNull($structures[0]);
        self::assertInstanceOf(Range2::class, $structures[0]);
    }
    
    public function testGetSeeAlso() {
        $seeAlso = $this->manifest->getSeeAlso();
        self::assertNotNull($seeAlso);
        self::assertTrue(is_array($seeAlso));
        
        // TODO
    }

    /**
     * Tests Manifest2->getContainedResourceById()
     */
    public function testGetContainedResourceById()
    {
        $containedSequence = $this->manifest->getContainedResourceById("http://example.org/iiif/book1/sequence/normal");
        self::assertNotNull($containedSequence);
        
        $defaultSequence = $this->manifest->getSequences()[0];
        self::assertNotNull($defaultSequence);
        /* @var $defaultSequence Sequence2 */
        
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
        self::assertEquals('1', $navDate->format('n'));
        self::assertEquals('1', $navDate->format('j'));
    }
    
    public function testEmptyManifest()
    {
        $json = parent::getFile('v2/empty-manifest.json');
        $manifest = Manifest2::loadIiifResource($json);
        
    }
    public function testDynamicProperties() {
        // All explicitly declared properties are protected. Ensure no additional public property is set after loading.
        self::assertEmpty(get_object_vars($this->manifest));
    }
    
    public function testGetRenderingUrlsForFormat() {
        $manifest = IiifHelper::loadIiifResource(parent::getFile('v2/manifest-sequence-rendering-01.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf', false);
        self::assertEmpty($rendering);
    
        $manifest = IiifHelper::loadIiifResource(parent::getFile('v2/manifest-sequence-rendering-01.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf');
        self::assertNotEmpty($rendering);
        self::assertTrue(is_array($rendering));
        self::assertEquals(2, sizeof($rendering));
        self::assertEquals("http://example.org/iiif/sequence-rendering.pdf", $rendering[0]);
        self::assertEquals("http://example.org/iiif/sequence-rendering-2.pdf", $rendering[1]);

        $manifest = IiifHelper::loadIiifResource(parent::getFile('v2/manifest-sequence-rendering-02.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf', false);
        self::assertNotEmpty($rendering);
        self::assertTrue(is_array($rendering));
        self::assertEquals(2, sizeof($rendering));
        self::assertEquals("http://example.org/iiif/manifest-rendering.pdf", $rendering[0]);
        self::assertEquals("http://example.org/iiif/manifest-rendering-2.pdf", $rendering[1]);
        
        $manifest = IiifHelper::loadIiifResource(parent::getFile('v2/manifest-sequence-rendering-02.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf');
        self::assertNotEmpty($rendering);
        self::assertTrue(is_array($rendering));
        self::assertEquals(2, sizeof($rendering));
        self::assertEquals("http://example.org/iiif/manifest-rendering.pdf", $rendering[0]);
        self::assertEquals("http://example.org/iiif/manifest-rendering-2.pdf", $rendering[1]);
    }
        
}

