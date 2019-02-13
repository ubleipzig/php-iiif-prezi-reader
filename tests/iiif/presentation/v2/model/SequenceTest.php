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

use iiif\AbstractIiifTest;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Sequence;

/**
 * Sequence test case.
 */
class SequenceTest extends AbstractIiifTest
{

    /**
     *
     * @var Sequence
     */
    private $sequence;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->json = parent::getFile('v2/manifest-example.json');
        $this->sequence = Manifest::loadIiifResource($this->json)->getSequences()[0];
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->sequence = null;
        
        parent::tearDown();
    }

    /**
     * Tests Sequence::fromArray()
     */
    public function testFromArray()
    {
        $json = parent::getFile('v2/sequence-example.json');
        $jsonAsArray = json_decode($json, true);
        $sequence = Sequence::loadIiifResource($jsonAsArray);
        self::assertNotNull($sequence);
        self::assertInstanceOf(Sequence::class, $sequence);
        self::assertequals("http://example.org/iiif/book1/sequence/normal", $sequence->getId());
    }

    /**
     * Tests Sequence->getCanvases()
     */
    public function testGetCanvases()
    {
        $canvases = $this->sequence->getCanvases();
        self::assertTrue(is_array($canvases));
        self::assertEquals(3, sizeof($canvases));
        foreach ($canvases as $canvas) {
            self::assertNotNull($canvas);
            self::assertInstanceOf(Canvas::class, $canvas);
        }
    }
    
    /**
     * Tests StartCanvasTrait->getStartCanvas()
     */
    public function testGetStartCanvas()
    {
        $startCanvas = $this->sequence->getStartCanvas();
        self::assertNull($startCanvas);

        $sequence = Sequence::loadIiifResource(parent::getFile('v2/sequence-example.json'));
        $startCanvas = $sequence->getStartCanvas();
        self::assertNotNull($startCanvas);
        self::assertEquals("http://example.org/iiif/book1/canvas/p2", $startCanvas->getId());
    }
    
    /**
     * Tests Sequence->getStartCanvasOrFirstCanvas()
     */
    public function testGetStartCanvasOrFirstCanvas()
    {
        $startCanvasOrFirstCanvas = $this->sequence->getStartCanvasOrFirstCanvas();
        self::assertNotNull($startCanvasOrFirstCanvas);
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $startCanvasOrFirstCanvas->getId());
        
        $sequence = Sequence::loadIiifResource(parent::getFile('v2/sequence-example.json'));
        $startCanvasOrFirstCanvas = $sequence->getStartCanvasOrFirstCanvas();
        self::assertNotNull($startCanvasOrFirstCanvas);
        self::assertEquals("http://example.org/iiif/book1/canvas/p2", $startCanvasOrFirstCanvas->getId());
    }
    public function testDynamicProperties() {
        // All explicitly declared properties are protected. Ensure no additional public property is set after loading.
        self::assertEmpty(get_object_vars($this->sequence));
    }
}

