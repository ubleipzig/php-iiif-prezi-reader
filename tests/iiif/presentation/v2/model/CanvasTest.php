<?php
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\AbstractIiifTest;
use iiif\presentation\v2\model\resources\AnnotationList;
use iiif\presentation\IiifHelper;

/**
 * Canvas test case.
 */
class CanvasTest extends AbstractIiifTest
{

    /**
     *
     * @var Canvas
     */
    private $canvas;
    private $json;

    protected function setUp()
    {
        $this->json = parent::getJson('canvas-example.json');
        $array = json_decode($this->json, true);
        $this->canvas = Canvas::loadIiifResource($array);
    }
    
    
    /**
     * Tests Canvas::fromArray()
     */
    public function testFromArray()
    {
        self::assertNotNull($this->canvas);
        self::assertInstanceOf(Canvas::class, $this->canvas);
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $this->canvas->getId());
        self::assertEquals("The label of the canvas", $this->canvas->getDefaultLabel());
    }

    /**
     * Tests Canvas->getImages()
     */
    public function testGetImages()
    {
        self::assertNotNull($this->canvas->getImages(),'No images.');
        self::assertNotEmpty($this->canvas->getImages(),'No images.');
    }

    /**
     * Tests Canvas->getOtherContent()
     */
    public function testGetOtherContent()
    {
        self::assertNotNull($this->canvas->getOtherContent(), 'otherContent is null.');
        self::assertNotEmpty($this->canvas->getOtherContent(), 'otherContent is empty.');
        self::assertInstanceOf(AnnotationList::class, $this->canvas->getOtherContent()[0], 'otherContent is not an AnnotationList array.');
    }
    
    public function testGetHeight()
    {
        self::assertEquals(1000, $this->canvas->getHeight());
    }

    public function testGetWidth()
    {
        self::assertEquals(750, $this->canvas->getWidth());
    }
    public function testGetThumbnail()
    {
        self::assertNotNull($this->canvas->getThumbnail(), 'No thumbnail.');
        self::assertNotNull($this->canvas->getThumbnail()->getImageUrl(), 'No thumbnail image URL.');
        self::assertEquals('http://example.org/iiif/book1/canvas/p1/thumb.jpg', $this->canvas->getThumbnail()->getImageUrl(), 'Wrong thumbnail image URL.');
        self::assertEquals(200, $this->canvas->getThumbnail()->getHeight(), 'Wrong thumbnail height.');
        self::assertEquals(150, $this->canvas->getThumbnail()->getWidth(), 'Wrong thumbnail width.');
    }
    public function testDynamicProperties() {
        // All explicitly declared properties are protected. Ensure no additional public property is set after loading.
        self::assertEmpty(get_object_vars($this->canvas));
    }
    public function testGetRenderingUrlsForFormat() {
        $manifest = IiifHelper::loadIiifResource(parent::getJson('canvas-rendering-01.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf');
        self::assertNotEmpty($rendering);
        self::assertEquals(2, sizeof($rendering));
        self::assertEquals("http://example.org/iiif/canvas-rendering.pdf", $rendering[0]);
        self::assertEquals("http://example.org/iiif/canvas-rendering-2.pdf", $rendering[1]);
        
        $manifest = IiifHelper::loadIiifResource(parent::getJson('canvas-rendering-02.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf');
        self::assertNotEmpty($rendering);
        self::assertEquals(2, sizeof($rendering));
        self::assertEquals("http://example.org/iiif/image-anno-rendering.pdf", $rendering[0]);
        self::assertEquals("http://example.org/iiif/image-anno-rendering-2.pdf", $rendering[1]);
        
        $manifest = IiifHelper::loadIiifResource(parent::getJson('canvas-rendering-02.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf', false);
        self::assertEmpty($rendering);

        $manifest = IiifHelper::loadIiifResource(parent::getJson('canvas-rendering-03.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf');
        self::assertNotEmpty($rendering);
        self::assertEquals(2, sizeof($rendering));
        self::assertEquals("http://example.org/iiif/image-resource-rendering.pdf", $rendering[0]);
        self::assertEquals("http://example.org/iiif/image-resource-rendering-2.pdf", $rendering[1]);
        
        $manifest = IiifHelper::loadIiifResource(parent::getJson('canvas-rendering-03.json'));
        $rendering = $manifest->getRenderingUrlsForFormat('application/pdf', false);
        self::assertEmpty($rendering);
    }
    
}

