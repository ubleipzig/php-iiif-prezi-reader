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

use Ubl\Iiif\Context\JsonLdHelper;
use Ubl\Iiif\Presentation\V2\Model\Constants\ViewingDirectionValues;
use Ubl\Iiif\Presentation\V3\Model\Constants\BehaviorValues;
use Ubl\Iiif\Presentation\V3\Model\Resources\AbstractIiifResource3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Annotation3;
use Ubl\Iiif\Presentation\V3\Model\Resources\AnnotationPage3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Canvas3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Collection3;
use Ubl\Iiif\Presentation\V3\Model\Resources\ContentResource3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Manifest3;
use Ubl\Iiif\Presentation\V3\Model\Resources\Range3;
use Ubl\Iiif\AbstractIiifTest;
use Ubl\Iiif\Tools\IiifHelper;

/**
 *  test case.
 */
class AbstractIiifResource3Test extends AbstractIiifTest
{

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // TODO Auto-generated AbstractIiifResource3Test::setUp()
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        // TODO Auto-generated AbstractIiifResource3Test::tearDown()
        parent::tearDown();
    }

    public function testLoadIiifResource() {
        $resource = "file://".__DIR__."/../../../../../resources/v3/manifest3-example.json";
        $iiifResource = AbstractIiifResource3::loadIiifResource($resource);
        
        self::assertNotNull($iiifResource);
        self::assertInstanceOf(Manifest3::class, $iiifResource);
        
        /* @var $iiifResource Manifest3 */
        
        self::assertEquals("https://example.org/iiif/book1/manifest", $iiifResource->getId());
        self::assertTrue(is_array($iiifResource->getLabel()));
        self::assertTrue(JsonLdHelper::isDictionary($iiifResource->getLabel()));
        self::assertEquals(["Book 1"], $iiifResource->getLabelTranslated());
        self::assertEquals(["Book 1"], $iiifResource->getLabelTranslated("en"));
        self::assertNull($iiifResource->getLabelTranslated("de"));
        
        self::assertNotNull($iiifResource->getMetadata());
        self::assertTrue(JsonLdHelper::isSimpleArray($iiifResource->getMetadata()));
        self::assertEquals(4, sizeof($iiifResource->getMetadata()));
        foreach ($iiifResource->getMetadata() as $metadatum) {
            self::assertTrue(array_key_exists("label", $metadatum));
            self::assertTrue(array_key_exists("value", $metadatum));
            self::assertTrue(JsonLdHelper::isDictionary($metadatum["label"]));
            self::assertTrue(JsonLdHelper::isDictionary($metadatum["value"]));
        }
        
        self::assertTrue(is_array($iiifResource->getLabel()));
        self::assertTrue(JsonLdHelper::isDictionary($iiifResource->getLabel()));
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
        self::assertTrue(JsonLdHelper::isDictionary($iiifResource->getSummary()));
        
        
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
        self::assertTrue(JsonLdHelper::isDictionary($iiifResource->getRequiredStatement()));
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
        self::assertTrue(JsonLdHelper::isSimpleArray($structures));
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
    
    public function testGetWeblinksForDisplay() {
        $manifest = IiifHelper::loadIiifResource(self::getFile("v3/manifest3-example.json"));
        /* @var $manifest \Ubl\Iiif\Presentation\V3\Model\Resources\Manifest3 */
        self::assertNotNull($manifest);
        self::assertNotNull($manifest->getWeblinksForDisplay());
        self::assertTrue(JsonLdHelper::isSimpleArray($manifest->getWeblinksForDisplay()));
        self::assertEquals(1, count($manifest->getWeblinksForDisplay()));
        $homepage = $manifest->getWeblinksForDisplay()[0];
        self::assertTrue(JsonLdHelper::isDictionary($homepage));
        self::assertEquals(3, count($homepage));
        self::assertEquals("https://example.org/info/book1/", $homepage["@id"]);
        self::assertEquals("Home page for Book 1", $homepage["label"]);
        self::assertEquals("text/html", $homepage["format"]);
    }
    
}