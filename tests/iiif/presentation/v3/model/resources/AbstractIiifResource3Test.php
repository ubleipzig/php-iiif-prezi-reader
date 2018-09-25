<?php

use iiif\context\JsonLdProcessor;
use iiif\presentation\v2\model\constants\ViewingDirectionValues;
use iiif\presentation\v3\model\constants\BehaviorValues;
use iiif\presentation\v3\model\resources\AbstractIiifResource3;
use iiif\presentation\v3\model\resources\Canvas3;
use iiif\presentation\v3\model\resources\Collection3;
use iiif\presentation\v3\model\resources\ContentResource3;
use iiif\presentation\v3\model\resources\Manifest3;
use iiif\presentation\v2\model\resources\Canvas;
use iiif\presentation\v3\model\resources\AnnotationPage3;
use iiif\presentation\v3\model\resources\Annotation3;
use iiif\presentation\v3\model\resources\Range3;

/**
 *  test case.
 */
class AbstractIiifResource3Test extends PHPUnit_Framework_TestCase
{

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        // TODO Auto-generated AbstractIiifResource3Test::setUp()
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated AbstractIiifResource3Test::tearDown()
        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }
    
    
    
    public function testLoadIiifResource() {
        $resource = "file://".__DIR__."/../../../../../resources/v3/manifest3-example.json";
        $iiifResource = AbstractIiifResource3::loadIiifResource($resource);
        
        self::assertNotNull($iiifResource);
        self::assertInstanceOf(Manifest3::class, $iiifResource);
        
        /* @var $iiifResource Manifest3 */
        
        self::assertEquals("https://example.org/iiif/book1/manifest", $iiifResource->getId());
        self::assertTrue(is_array($iiifResource->getLabel()));
        self::assertTrue(JsonLdProcessor::isDictionary($iiifResource->getLabel()));
        self::assertEquals(["Book 1"], $iiifResource->getLabelTranslated());
        self::assertEquals(["Book 1"], $iiifResource->getLabelTranslated("en"));
        self::assertNull($iiifResource->getLabelTranslated("de"));
        
        self::assertNotNull($iiifResource->getMetadata());
        self::assertTrue(JsonLdProcessor::isSequentialArray($iiifResource->getMetadata()));
        self::assertEquals(4, sizeof($iiifResource->getMetadata()));
        foreach ($iiifResource->getMetadata() as $metadatum) {
            self::assertTrue(array_key_exists("label", $metadatum));
            self::assertTrue(array_key_exists("value", $metadatum));
            self::assertTrue(JsonLdProcessor::isDictionary($metadatum["label"]));
            self::assertTrue(JsonLdProcessor::isDictionary($metadatum["value"]));
        }
        
        self::assertTrue(is_array($iiifResource->getLabel()));
        self::assertTrue(JsonLdProcessor::isDictionary($iiifResource->getLabel()));
        self::assertEquals(["Book 1"], $iiifResource->getLabelTranslated());
        self::assertEquals(["Book 1"], $iiifResource->getLabelTranslated("en"));
        self::assertNull($iiifResource->getLabelTranslated("de"));
        
        self::assertEquals(["Anne Author"], $iiifResource->getMetadataForLabel("Author"));
        self::assertEquals(["Anne Author"], $iiifResource->getMetadataForLabel("Author", "en"));
        self::assertEquals(["Anne Author"], $iiifResource->getMetadataForLabel("Author", "de"));
        
        self::assertEquals(["Paris, circa 1400"], $iiifResource->getMetadataForLabel("Published"));
        self::assertEquals(["Paris, circa 1400"], $iiifResource->getMetadataForLabel("Published", "en"));
        self::assertEquals(["Paris, environ 1400"], $iiifResource->getMetadataForLabel("Published", "fr"));
        self::assertEquals(null, $iiifResource->getMetadataForLabel("Published", "de"));
        
        self::assertNotNull($iiifResource->getSummary());
        self::assertTrue(JsonLdProcessor::isDictionary($iiifResource->getSummary()));
        
        
        $thumbnails = $iiifResource->getThumbnail();
        self::assertNotNull($thumbnails);
        self::assertTrue(is_array($thumbnails));
        self::assertEquals(1, sizeof($thumbnails));
        $thumbnail = $iiifResource->getThumbnail()[0];
        self::assertInstanceOf(ContentResource3::class, $thumbnail);
        /* @var $thumbnail ContentResource3 */
        self::assertEquals("https://example.org/images/book1-page1/full/80,100/0/default.jpg", $thumbnail->getId());
        
        self::assertNotNull($iiifResource->getViewingDirection());
        self::assertEquals(ViewingDirectionValues::RIGHT_TO_LEFT, $iiifResource->getViewingDirection());
        
        self::assertNotNull($iiifResource->getBehavior());
        self::assertEquals(BehaviorValues::PAGED, $iiifResource->getBehavior()[0]);
        
        self::assertNotNull($iiifResource->getNavDate());
        self::assertEquals("1856-01-01T00:00:00Z", $iiifResource->getNavDate());

        self::assertNotNull($iiifResource->getRights());
        self::assertEquals("https://creativecommons.org/licenses/by/4.0/", $iiifResource->getRights());
        
        self::assertNotNull($iiifResource->getRequiredStatement());
        self::assertTrue(JsonLdProcessor::isDictionary($iiifResource->getRequiredStatement()));
        self::assertTrue(array_key_exists("label", $iiifResource->getRequiredStatement()));
        self::assertTrue(array_key_exists("value", $iiifResource->getRequiredStatement()));
        
        self::assertNotNull($iiifResource->getLogo());
        self::assertInstanceOf(ContentResource3::class, $iiifResource->getLogo());
        
        self::assertNotNull($iiifResource->getHomepage());
        self::assertNotNull($iiifResource->getService());
        self::assertNotNull($iiifResource->getSeeAlso());
        self::assertNotNull($iiifResource->getRendering());
        
        self::assertNotNull($iiifResource->getPartOf());
        self::assertTrue(is_array($iiifResource->getPartOf()));
        self::assertInstanceOf(Collection3::class, $iiifResource->getPartOf()[0]);
        self::assertFalse($iiifResource->getPartOf()[0]->isInitialized());
        
        self::assertNotNull($iiifResource->getStart());
        self::assertInstanceOf(Canvas3::class, $iiifResource->getStart());
        self::assertTrue($iiifResource->getStart()->isInitialized());
        // ensure that the start canvas is the same object as the canvas with the same id contained in items
        self::assertEquals(["p. 2"], $iiifResource->getStart()->getLabelTranslated());
        
        $items = $iiifResource->getItems();
        self::assertNotNull($items);
        self::assertTrue(is_array($items));
        self::assertEquals(2, sizeof($items));
        $canvas1 = $items[0];
        $canvas2 = $items[1];
        self::assertInstanceOf(Canvas3::class, $canvas1);
        self::assertInstanceOf(Canvas3::class, $canvas2);
        /* @var $canvas1 Canvas3 */
        self::assertEquals("https://example.org/iiif/book1/canvas/p1", $canvas1->getId());
        self::assertEquals(["p. 1"], $canvas1->getLabelTranslated());
        
        $annotationsPages = $canvas1->getItems();
        self::assertNotNull($annotationsPages);
        self::assertTrue(is_array($annotationsPages));
        self::assertEquals(1, sizeof($annotationsPages));
        $annotationPage = $annotationsPages[0];
        self::assertInstanceOf(AnnotationPage3::class, $annotationPage);
        /* @var $annotationPage AnnotationPage3 */
        self::assertEquals("https://example.org/iiif/book1/page/p1/1", $annotationPage->getId());
        
        $annotations = $annotationPage->getItems();
        self::assertNotNull($annotations);
        self::assertTrue(is_array($annotations));
        self::assertEquals(1, sizeof($annotations));
        
        $annotation = $annotations[0];
        self::assertNotNull($annotation);
        self::assertInstanceOf(Annotation3::class, $annotation);
        /* @var $annotation Annotation3 */
        self::assertEquals("https://example.org/iiif/book1/annotation/p0001-image", $annotation->getId());
        
        $contentResource = $annotation->getBody();
        self::assertNotNull($contentResource);
        self::assertInstanceOf(ContentResource3::class, $contentResource);
        
        $targetCanvas = $annotation->getTarget();
        self::assertNotNull($targetCanvas);
        self::assertInstanceOf(Canvas3::class, $targetCanvas);
        self::assertTrue($targetCanvas->isInitialized());
        
        
        $structures = $iiifResource->getStructures();
        self::assertNotNull($structures);
        self::assertTrue(JsonLdProcessor::isSequentialArray($structures));
        self::assertEquals(1, sizeof($structures));
        
        $toc = $structures[0];
        self::assertNotNull($toc);
        self::assertInstanceOf(Range3::class, $toc);
        self::assertEquals("https://example.org/iiif/book1/range/r0", $toc->getId());
        
        self::assertNotNull($iiifResource->getAnnotations());
        // TODO 
        
        
        $resourceUBL = "file://".__DIR__."/../../../../../resources/v3/manifest-00000004119.json";
        $iiifResourceUBL = AbstractIiifResource3::loadIiifResource($resourceUBL);
        
        self::assertInstanceOf(Manifest3::class, $iiifResourceUBL);
        
        $this->markTestIncomplete("getLoadIiifResource test not implemented");
    }
}

;