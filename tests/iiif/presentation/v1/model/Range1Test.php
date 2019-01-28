<?php
use iiif\presentation\v1\model\resources\Range1;
use iiif\AbstractIiifTest;
use iiif\tools\IiifHelper;
use iiif\presentation\v1\model\resources\Manifest1;
use iiif\presentation\v1\model\resources\Canvas1;

require_once 'iiif/presentation/v1/model/resources/Range1.php';

/**
 * Range1 test case.
 */
class Range1Test extends AbstractIiifTest {


    /**
     * @var Manifest1
     */
    protected $manifest;
    
    /**
     * 
     * @var Range1[]
     */
    protected $structures;
    
    protected $expectedCanvasesPerRange;
    protected $expectedRecursiveCanvasesPerRange;
    protected $expectedFirstCanvas;
    protected $expectedChildRanges;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        $this->manifest = IiifHelper::loadIiifResource(self::getJson("v1/range-example.json"));
        $this->structures = $this->manifest->getStructures();
        $this->expectedCanvasesPerRange = [
            "http://www.example.org/iiif/item1/range/range1.json" => [
                "http://www.example.org/iiif/item1/canvas/page1.json",
                "http://www.example.org/iiif/item1/canvas/page2.json"
            ],
            "http://www.example.org/iiif/item1/range/range2.json" => [
                "http://www.example.org/iiif/item1/canvas/page2.json",
                "http://www.example.org/iiif/item1/canvas/page3.json"
            ],
            "http://www.example.org/iiif/item1/range/range2-1.json" => [
                "http://www.example.org/iiif/item1/canvas/page3.json"
            ],
            "http://www.example.org/iiif/item1/range/range2-2.json" => [
                "http://www.example.org/iiif/item1/canvas/page4.json"
            ],
            "http://www.example.org/iiif/item1/range/range3.json" => [
                "http://www.example.org/iiif/item1/canvas/page5.json"
            ]
        ];
        $this->expectedRecursiveCanvasesPerRange = [
            "http://www.example.org/iiif/item1/range/range1.json" => [
                "http://www.example.org/iiif/item1/canvas/page1.json",
                "http://www.example.org/iiif/item1/canvas/page2.json"
            ],
            "http://www.example.org/iiif/item1/range/range2.json" => [
                "http://www.example.org/iiif/item1/canvas/page2.json",
                "http://www.example.org/iiif/item1/canvas/page3.json",
                "http://www.example.org/iiif/item1/canvas/page4.json"
            ],
            "http://www.example.org/iiif/item1/range/range2-1.json" => [
                "http://www.example.org/iiif/item1/canvas/page3.json"
            ],
            "http://www.example.org/iiif/item1/range/range2-2.json" => [
                "http://www.example.org/iiif/item1/canvas/page4.json"
            ],
            "http://www.example.org/iiif/item1/range/range3.json" => [
                "http://www.example.org/iiif/item1/canvas/page5.json"
            ]
        ];
        $this->expectedFirstCanvas = [
            "http://www.example.org/iiif/item1/range/range1.json" => "http://www.example.org/iiif/item1/canvas/page1.json",
            "http://www.example.org/iiif/item1/range/range2.json" => "http://www.example.org/iiif/item1/canvas/page2.json",
            "http://www.example.org/iiif/item1/range/range2-1.json" => "http://www.example.org/iiif/item1/canvas/page3.json",
            "http://www.example.org/iiif/item1/range/range2-2.json" => "http://www.example.org/iiif/item1/canvas/page4.json",
            "http://www.example.org/iiif/item1/range/range3.json" => "http://www.example.org/iiif/item1/canvas/page5.json",
        ];
        $this->expectedChildRanges = [
            "http://www.example.org/iiif/item1/range/range1.json" => [],
            "http://www.example.org/iiif/item1/range/range2.json" =>
            [
                "http://www.example.org/iiif/item1/range/range2-1.json",
                "http://www.example.org/iiif/item1/range/range2-2.json"
            ],
            "http://www.example.org/iiif/item1/range/range2-1.json" => [],
            "http://www.example.org/iiif/item1/range/range2-2.json" => [],
            "http://www.example.org/iiif/item1/range/range3.json" => [],
        ];
        
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->manifest = null;
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->initTreeHierarchy()
     */
    public function testInitTreeHierarchy() {
        // TODO Auto-generated Range1Test->testInitTreeHierarchy()
        $this->markTestIncomplete("initTreeHierarchy test not implemented");
        
        $this->range1->initTreeHierarchy(/* parameters */);
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getCanvases()
     */
    public function testGetCanvases() {
        foreach ($this->structures as $range) {
            $canvasIds = [];
            foreach ($range->getCanvases() as $canvas) {
                $canvasIds[] = $canvas->getId();
            }
            self::assertEquals($this->expectedCanvasesPerRange[$range->getId()], $canvasIds);
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getWithin()
     */
    public function testGetWithin() {
        $expectedWithin = [
            "http://www.example.org/iiif/item1/range/range1.json" => null,
            "http://www.example.org/iiif/item1/range/range2.json" => null,
            "http://www.example.org/iiif/item1/range/range2-1.json" => "http://www.example.org/iiif/item1/range/range2.json",
            "http://www.example.org/iiif/item1/range/range2-2.json" => "http://www.example.org/iiif/item1/range/range2.json",
            "http://www.example.org/iiif/item1/range/range3.json" => null
        ];
        foreach ($this->structures as $range) {
            $expectedId = $expectedWithin[$range->getId()];
            if ($expectedId == null) {
                self::assertNull($range->getWithin(), $range->getId()." - 'within' must be null.");
            } else {
                self::assertInstanceOf(Range1::class, $range->getWithin());
                self::assertEquals($expectedId, $range->getWithin()->getId(), $range->getId()." - 'within' must be ".$expectedId." but is ".$range->getWithin()->getId().".");
            }
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getAllCanvases()
     */
    public function testGetAllCanvases() {
        foreach ($this->structures as $range) {
            $canvasIds = [];
            foreach ($range->getAllCanvases() as $canvas) {
                $canvasIds[] = $canvas->getId();
            }
            self::assertEquals($this->expectedCanvasesPerRange[$range->getId()], $canvasIds);
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getAllCanvasesRecursively()
     */
    public function testGetAllCanvasesRecursively() {
        foreach ($this->structures as $range) {
            $canvasIds = [];
            foreach ($range->getAllCanvasesRecursively() as $canvas) {
                $canvasIds[] = $canvas->getId();
            }
            self::assertEquals($this->expectedRecursiveCanvasesPerRange[$range->getId()], $canvasIds);
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getAllItems()
     */
    public function testGetAllItems() {
        foreach ($this->structures as $range) {
            $expectedItemIds = array_merge($this->expectedCanvasesPerRange[$range->getId()], $this->expectedChildRanges[$range->getId()]);
            $actualItems = [];
            foreach ($range->getAllItems() as $item) {
                self::assertTrue($item instanceof Range1 || $item instanceof Canvas1);
                $actualItems[] = $item->getId();
            }
            self::assertEquals($expectedItemIds, $actualItems);
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getAllRanges()
     */
    public function testGetAllRanges() {
        foreach ($this->structures as $range) {
            $childRangeIds = [];
            if (!empty($range->getAllRanges())) {
                foreach ($range->getAllRanges() as $childRange) {
                    $childRangeIds[] = $childRange->getId();
                }
            }
            self::assertEquals($this->expectedChildRanges[$range->getId()], $childRangeIds);
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getStartCanvas()
     */
    public function testGetStartCanvas() {
        foreach ($this->structures as $range) {
            self::assertNull($range->getStartCanvas());
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->getStartCanvasOrFirstCanvas()
     */
    public function testGetStartCanvasOrFirstCanvas() {
        foreach ($this->structures as $range) {
            self::assertNotNull($range->getStartCanvasOrFirstCanvas());
            self::assertInstanceOf(Canvas1::class, $range->getStartCanvasOrFirstCanvas());
            self::assertEquals($this->expectedFirstCanvas[$range->getId()], $range->getStartCanvasOrFirstCanvas()->getId());
        }
    }

    /**
     * Tests iiif\presentation\v1\model\resources\Range1->isTopRange()
     */
    public function testIsTopRange() {
        self::assertEquals(5, count($this->structures));
        foreach ($this->structures as $range) {
            self::assertFalse($range->isTopRange(), $range->getId()." must not be a top range.");
        }
    }
}

