<?php
use iiif\model\resources\Canvas;

require_once 'iiif/model/resources/Canvas.php';

/**
 * Canvas test case.
 */
class CanvasTest extends PHPUnit_Framework_TestCase
{

    /**
     *
     * @var Canvas
     */
    private $canvas;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated CanvasTest::setUp()
        
        $this->canvas = new Canvas(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated CanvasTest::tearDown()
        $this->canvas = null;
        
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
     * Tests Canvas::fromArray()
     */
    public function testFromArray()
    {
        $json = file_get_contents(__DIR__.'/../../resources/canvas-example.json');
        $array = json_decode($json, true);
        $canvas = Canvas::fromArray($array);
        
        self::assertNotNull($canvas);
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $canvas->getId());
        self::assertEquals("The label of the canvas", $canvas->getDefaultLabel());
    }

    /**
     * Tests Canvas->getImages()
     */
    public function testGetImages()
    {
        // TODO Auto-generated CanvasTest->testGetImages()
        $this->markTestIncomplete("getImages test not implemented");
        
        $this->canvas->getImages(/* parameters */);
    }

    /**
     * Tests Canvas->getOtherContent()
     */
    public function testGetOtherContent()
    {
        // TODO Auto-generated CanvasTest->testGetOtherContent()
        $this->markTestIncomplete("getOtherContent test not implemented");
        
        $this->canvas->getOtherContent(/* parameters */);
    }
}

