<?php
namespace iiif\presentation\v2\model;

use iiif\AbstractIiifTest;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\resources\Manifest;
use iiif\presentation\v2\model\resources\Range;

class RangeTest extends AbstractIiifTest
{
    /**
     * @var Manifest
     */
    private $manifest;
    
    protected function setup()
    {
        $jsonAsString = parent::getJson('structures-example.json');
        $this->manifest = Manifest::loadIiifResource($jsonAsString);
    }
    
    public function testStructures()
    {
        self::assertNotNull($this->manifest->getStructures(), 'structures is null.');
        self::assertNotEmpty($this->manifest->getStructures(), 'No structures.');
        foreach ($this->manifest->getStructures() as $structure)
        {
            self::assertInstanceOf(Range::class, $structure, 'Structure is not a Range.');
        }
    }
    public function testMembers()
    {
        self::assertNotNull($this->manifest->getStructures()[0]);
        $range1 = $this->manifest->getStructures()[0];
        /* @var $range1 Range */
        self::assertInstanceOf(Range::class, $range1);
        self::assertEquals('http://example.org/iiif/book1/range/r0', $range1->getId());
        self::assertEquals('Table of Contents', $range1->getLabelForDisplay());
        self::assertEmpty($range1->getCanvases());
        self::assertEmpty($range1->getRanges());
        self::assertNotEmpty($range1->getMembers());
        foreach ($range1->getMembers() as $member)
        {
            self::assertTrue($range1 instanceof Canvas || $range1 instanceof Range);
        }
    }
    public function testRanges()
    {
        self::assertNotNull($this->manifest->getStructures()[1]);
        $range2 = $this->manifest->getStructures()[1];
        /* @var $range2 Range */
        self::assertInstanceOf(Range::class, $range2);
        self::assertEquals('http://example.org/iiif/book1/range/r1', $range2->getId());
        self::assertEquals('Introduction', $range2->getLabelForDisplay());
        self::assertEmpty($range2->getMembers());
        self::assertNotEmpty($range2->getRanges());
        self::assertNotEmpty($range2->getCanvases());
        
        self::assertEquals(3, count($range2->getCanvases()));
        foreach ($range2->getCanvases() as $canvas)
        {
            self::assertInstanceOf(Canvas::class, $canvas);
            /* @var $canvas Canvas */
            self::assertTrue(!$canvas->isInitialized() || $canvas->getId() == 'http://example.org/iiif/book1/canvas/p3');
        }
        self::assertEquals('http://example.org/iiif/book1/canvas/p1', $range2->getCanvases()[0]->getId(), 'Wrong id for canvas 0: '.$range2->getCanvases()[0]->getId());
        self::assertEquals('http://example.org/iiif/book1/canvas/p3', $range2->getCanvases()[2]->getId(), 'Wrong id for canvas 2: '.$range2->getCanvases()[2]->getId());

        self::assertEquals(1, count($range2->getRanges()));
        self::assertInstanceOf(Range::class, $range2->getRanges()[0]);
        self::assertTrue($range2->getRanges()[0]->isInitialized());
        self::assertEquals("Objectives and Scope", $range2->getRanges()[0]->getLabel());
    }
    public function testCanvases()
    {
        self::assertNotNull($this->manifest->getStructures()[2]);
        $range3 = $this->manifest->getStructures()[2];
        /* @var $range3 Range */
        self::assertInstanceOf(Range::class, $range3);
        self::assertEquals('http://example.org/iiif/book1/range/r1-1', $range3->getId(), 'Wrong range id.');
        self::assertEquals('Objectives and Scope', $range3->getLabelForDisplay(), 'Wrong label.');
        self::assertEmpty($range3->getMembers());
        self::assertEmpty($range3->getRanges());
        self::assertNotEmpty($range3->getCanvases());
        
        self::assertEquals(1, count($range3->getCanvases()));
        self::assertInstanceOf(Canvas::class, $range3->getCanvases()[0]);
        self::assertEquals('http://example.org/iiif/book1/canvas/p2', $range3->getCanvases()[0]->getId(), 'Wrong canvas id.');
        self::assertTrue(!$range3->getCanvases()[0]->isInitialized());
    }
    
    public function testGetStartCanvas()
    {
        $range0 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r0');
        self::assertNotNull($range0);
        /* @var $range0 Range  */
        self::assertNull($range0->getStartCanvas());
        
        
        $range1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1');
        self::assertNotNull($range1);
        /* @var $range1 Range  */
        $canvasRange1 = $range1->getStartCanvas();
        self::assertNotNull($canvasRange1);
        self::assertEquals('http://example.org/iiif/book1/canvas/p2', $canvasRange1->getId());
        
        $range1_1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1-1');
        self::assertNotNull($range1_1);
        /* @var $range11 Range  */
        self::assertNull($range1_1->getStartCanvas());
    }

    public function testGetStartCanvasOrFirstCanvas()
    {
        $range0 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r0');
        self::assertNotNull($range0);
        /* @var $range0 Range  */
        $canvasRange0 = $range0->getStartCanvasOrFirstCanvas();
        self::assertNotNull($canvasRange0);
        self::assertEquals('http://example.org/iiif/book1/canvas/cover', $canvasRange0->getId());
        
        $range1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1');
        self::assertNotNull($range1);
        /* @var $range1 Range  */
        $canvasRange1 = $range1->getStartCanvasOrFirstCanvas();
        self::assertNotNull($canvasRange1);
        self::assertEquals('http://example.org/iiif/book1/canvas/p2', $canvasRange1->getId());
        
        $range1_1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1-1');
        self::assertNotNull($range1_1);
        /* @var $range1_1 Range  */
        $canvasRange1_1 = $range1_1->getStartCanvasOrFirstCanvas();
        self::assertNotNull($canvasRange1_1);
        self::assertEquals('http://example.org/iiif/book1/canvas/p2', $canvasRange1_1->getId());
    }

    public function testDynamicProperties() {
        // All explicitly declared properties are protected. Ensure no additional public property is set after loading.
        self::assertEmpty(get_object_vars($this->manifest));
    }

    public function testGetMemberRangesAndRanges() {
        $range0 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r0');
        self::assertNotNull($range0);
        self::assertEmpty($range0->getRanges());
        self::assertNotEmpty($range0->getMembers());
        self::assertEquals(3, sizeof($range0->getMembers()));
        $range0_ranges = $range0->getMemberRangesAndRanges();
        self::assertNotEmpty($range0_ranges);
        self::assertEquals(1, sizeof($range0_ranges));
        self::assertEquals('http://example.org/iiif/book1/range/r1', $range0_ranges[0]->getId());
        
        $range1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1');
        self::assertNotNull($range1);
        self::assertEquals($range1, $range0_ranges[0]);
        self::assertEmpty($range1->getMembers());
        self::assertNotEmpty($range1->getRanges());
        self::assertEquals(1, sizeof($range1->getRanges()));
        self::assertNotEmpty($range1->getMemberRangesAndRanges());
        self::assertEquals(1, sizeof($range1->getMemberRangesAndRanges()));
        self::assertEquals($range1->getRanges()[0], $range1->getMemberRangesAndRanges()[0]);
        
        $range1_1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1-1');
        self::assertNotNull($range1_1);
        self::assertEmpty($range1_1->getMembers());
        self::assertEmpty($range1_1->getRanges());
        self::assertEmpty($range1_1->getMemberRangesAndRanges());
        
        
        $range1_2 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1-2');
        self::assertNotNull($range1_2);
        self::assertEmpty($range1_2->getMembers());
        self::assertNotEmpty($range1_2->getRanges());
        self::assertEquals(1, sizeof($range1_2->getRanges()));
        self::assertNotEmpty($range1_2->getMemberRangesAndRanges());
        self::assertEquals(1, sizeof($range1_2->getMemberRangesAndRanges()));
        self::assertEquals('http://example.org/iiif/book1/range/r1-2-1', $range1_2->getMemberRangesAndRanges()[0]->getId());
        
        $range1_2_1 = $this->manifest->getContainedResourceById('http://example.org/iiif/book1/range/r1-2-1');
        self::assertNotNull($range1_2_1);
        self::assertEquals($range1_2_1, $range1_2->getMemberRangesAndRanges()[0]);
        self::assertEmpty($range1_2_1->getMembers());
        self::assertEmpty($range1_2_1->getRanges());
        self::assertEmpty($range1_2_1->getMemberRangesAndRanges());
    }
}

