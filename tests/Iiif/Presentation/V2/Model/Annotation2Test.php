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
use Ubl\Iiif\Presentation\Common\Vocabulary\Motivation;
use Ubl\Iiif\Presentation\V2\Model\Resources\Annotation2;
use Ubl\Iiif\Presentation\V2\Model\Resources\Canvas2;
use Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource2;
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\Presentation\Common\Model\XYWHFragment;

/**
 * Annotation test case.
 */
class Annotation2Test extends AbstractIiifTest {

    /**
     * @var Annotation2
     */
    private $imageAnnotation;
    
    /**
     * @var Annotation2
     */
    private $textAnnotation;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->imageAnnotation = IiifHelper::loadIiifResource(parent::getFile("v2/annotation-image-example.json"));
        $this->textAnnotation = IiifHelper::loadIiifResource(parent::getFile("v2/annotation-text-example.json"));
        self::assertInstanceOf(Annotation2::class, $this->imageAnnotation);
        self::assertInstanceOf(Annotation2::class, $this->textAnnotation);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        $this->imageAnnotation = null;
        $this->textAnnotation = null;
        parent::tearDown();
    }

    /**
     * Tests Annotation2->getResource()
     */
    public function testGetResource() {
        self::assertNotNull($this->imageAnnotation->getResource());
        self::assertInstanceOf(ContentResource2::class, $this->imageAnnotation->getResource());
        self::assertEquals("http://example.org/iiif/book1/res/page1.jpg", $this->imageAnnotation->getResource()->getId());

        self::assertNotNull($this->textAnnotation->getResource());
        self::assertInstanceOf(ContentResource2::class, $this->textAnnotation->getResource());
        self::assertNull($this->textAnnotation->getResource()->getId());
    }

    /**
     * Tests Annotation2->getOn()
     */
    public function testGetOn() {
        $imageOn = $this->imageAnnotation->getOn();
        self::assertNotNull($imageOn);
        self::assertInstanceOf(Canvas2::class, $imageOn);
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $imageOn->getId());

        $textOn = $this->textAnnotation->getOn();
        self::assertNotNull($textOn);
        self::assertInstanceOf(XYWHFragment::class, $textOn);
        self::assertEquals(100, $textOn->getX());
        self::assertEquals(150, $textOn->getY());
        self::assertEquals(500, $textOn->getWidth());
        self::assertEquals(25, $textOn->getHeight());
        self::assertNotNull($textOn->getTargetObject());
        self::assertInstanceOf(Canvas2::class, $textOn->getTargetObject());
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $textOn->getTargetObject()->getId());
        self::assertEquals("xywh=100,150,500,25", $textOn->getFragment());
        
        self::markTestIncomplete("Assert that canvas in XYWHFragment identical to Canvas which contains the annotationslist / images");
    }

    /**
     * Tests Annotation2->getMotivation()
     */
    public function testGetMotivation() {
        self::assertNotNull($this->imageAnnotation);
        self::assertEquals(Motivation::IIIF_PRESENTATION2_PAINTING, $this->imageAnnotation->getMotivation());
        
        self::assertNotNull($this->textAnnotation);
        self::assertEquals(Motivation::OA_COMMENTING, $this->textAnnotation->getMotivation());
    }
    
    public function testGetOnSelector() {
        /**
         * @var \Ubl\Iiif\Presentation\V2\Model\Resources\Canvas2 $canvas
         */
        $canvas = IiifHelper::loadIiifResource(parent::getFile("v2/canvas-with-annotations.json"));
        self::assertInstanceOf(Canvas2::class, $canvas);
        $images = $canvas->getImageAnnotations();
        self::assertEquals(3, sizeof($images));
        $annotation1 = $images[0];
        self::assertInstanceOf(Annotation2::class, $annotation1);
        self::assertNull($annotation1->getOnSelector());
        $annotation2 = $images[2];
        self::assertInstanceOf(Annotation2::class, $annotation1);
        self::assertNotNull($annotation2->getOnSelector());
        self::assertInstanceOf(XYWHFragment::class, $annotation2->getOnSelector());
    }

    public function testGetTargetResourceId() {
        /**
         * @var \Ubl\Iiif\Presentation\V2\Model\Resources\Canvas2 $canvas
         */
        $canvas = IiifHelper::loadIiifResource(parent::getFile("v2/canvas-with-annotations.json"));
        $images = $canvas->getImageAnnotations();
        $annotation1 = $images[0];
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $annotation1->getTargetResourceId());
        $annotation2 = $images[2];
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $annotation2->getTargetResourceId());
    }
    
}

