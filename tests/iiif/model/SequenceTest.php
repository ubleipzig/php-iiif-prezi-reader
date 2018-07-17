<?php
use iiif\model\resources\Sequence;
use iiif\model\resources\Manifest;
use iiif\model\resources\Canvas;
use iiif\model\AbstractIiifTest;

require_once 'iiif/model/resources/Sequence.php';

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
        
        $this->json = parent::getJson('manifest-example.json');
        $this->sequence = Manifest::fromJson($this->json)->getSequences()[0];
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
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    /**
     * Tests Sequence::fromArray()
     */
    public function testFromArray()
    {
        $json = parent::getJson('sequence-example.json');
        $jsonAsArray = json_decode($json, true);
        $sequence = Sequence::fromArray($jsonAsArray);
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

        $sequence = Sequence::fromJson(parent::getJson('sequence-example.json'));
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
        
        $sequence = Sequence::fromJson(parent::getJson('sequence-example.json'));
        $startCanvasOrFirstCanvas = $sequence->getStartCanvasOrFirstCanvas();
        self::assertNotNull($startCanvasOrFirstCanvas);
        self::assertEquals("http://example.org/iiif/book1/canvas/p2", $startCanvasOrFirstCanvas->getId());
    }
}

