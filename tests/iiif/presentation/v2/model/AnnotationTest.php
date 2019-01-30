<?php
use iiif\AbstractIiifTest;
use iiif\presentation\v2\model\properties\XYWHFragment;
use iiif\presentation\v2\model\resources\Annotation;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v2\model\resources\ContentResource;
use iiif\tools\IiifHelper;
use iiif\presentation\common\vocabulary\Motivation;

/**
 * Annotation test case.
 */
class AnnotationTest extends AbstractIiifTest {

    /**
     * @var Annotation
     */
    private $imageAnnotation;
    
    /**
     * @var Annotation
     */
    private $textAnnotation;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp() {
        parent::setUp();
        $this->imageAnnotation = IiifHelper::loadIiifResource(parent::getFile("v2/annotation-image-example.json"));
        $this->textAnnotation = IiifHelper::loadIiifResource(parent::getFile("v2/annotation-text-example.json"));
        self::assertInstanceOf(Annotation::class, $this->imageAnnotation);
        self::assertInstanceOf(Annotation::class, $this->textAnnotation);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown() {
        $this->imageAnnotation = null;
        $this->textAnnotation = null;
        parent::tearDown();
    }

    /**
     * Tests Annotation->getResource()
     */
    public function testGetResource() {
        self::assertNotNull($this->imageAnnotation->getResource());
        self::assertInstanceOf(ContentResource::class, $this->imageAnnotation->getResource());
        self::assertEquals("http://example.org/iiif/book1/res/page1.jpg", $this->imageAnnotation->getResource()->getId());

        self::assertNotNull($this->textAnnotation->getResource());
        self::assertInstanceOf(ContentResource::class, $this->textAnnotation->getResource());
        self::assertNull($this->textAnnotation->getResource()->getId());
    }

    /**
     * Tests Annotation->getOn()
     */
    public function testGetOn() {
        $imageOn = $this->imageAnnotation->getOn();
        self::assertNotNull($imageOn);
        self::assertInstanceOf(Canvas::class, $imageOn);
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $imageOn->getId());

        $textOn = $this->textAnnotation->getOn();
        self::assertNotNull($textOn);
        self::assertInstanceOf(XYWHFragment::class, $textOn);
        self::assertEquals(100, $textOn->getX());
        self::assertEquals(150, $textOn->getY());
        self::assertEquals(500, $textOn->getWidth());
        self::assertEquals(25, $textOn->getHeight());
        self::assertNotNull($textOn->getTargetObject());
        self::assertInstanceOf(Canvas::class, $textOn->getTargetObject());
        self::assertEquals("http://example.org/iiif/book1/canvas/p1", $textOn->getTargetObject()->getId());
        self::assertEquals("xywh=100,150,500,25", $textOn->getFragment());
        
        self::markTestIncomplete("Assert that canvas in XYWHFragment identical to Canvas which contains the annotationslist / images");
    }

    /**
     * Tests Annotation->getMotivation()
     */
    public function testGetMotivation() {
        self::assertNotNull($this->imageAnnotation);
        self::assertEquals(Motivation::IIIF_PRESENTATION2_PAINTING, $this->imageAnnotation->getMotivation());
        
        self::assertNotNull($this->textAnnotation);
        self::assertEquals(Motivation::OA_COMMENTING, $this->textAnnotation->getMotivation());
    }
}

