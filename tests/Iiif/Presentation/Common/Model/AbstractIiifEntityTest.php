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

use Ubl\Iiif\Presentation\Common\Model\Resources\IiifResourceInterface;
use Ubl\Iiif\Tools\IiifHelper;
use Ubl\Iiif\AbstractIiifTest;
use Ubl\Iiif\Presentation\V2\Model\Resources\Manifest;
use Ubl\Iiif\Presentation\V2\Model\Resources\Canvas;
use Ubl\Iiif\Presentation\V2\Model\Resources\Annotation;
use Ubl\Iiif\Presentation\V2\Model\Resources\ContentResource;
use Ubl\Iiif\Services\ImageInformation2;

/**
 * AbstractIiifEntity test case.
 */
class AbstractIiifEntityTest extends AbstractIiifTest {

    public function testSanitizeHTML() {
        $options = IiifResourceInterface::SANITIZE_NO_TAGS|IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML;
        self::assertEquals(5, $options);
        self::assertEquals(IiifResourceInterface::SANITIZE_NO_TAGS, $options&IiifResourceInterface::SANITIZE_NO_TAGS);
        self::assertEquals(IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML, $options&IiifResourceInterface::SANITIZE_XML_ENCODE_NONHTML);
        self::assertEquals(0, $options&IiifResourceInterface::SANITIZE_XML_ENCODE_ALL);
        
        $doc = parent::getFile("v2/manifest-html-metadata.json");
        $manifest = IiifHelper::loadIiifResource($doc);
        
        /* @var $manifest Manifest */
        
        self::assertInstanceOf(Manifest::class, $manifest);
        self::assertNotEmpty($manifest->getMetadataForDisplay());
        
        self::assertEquals("No HTML", $manifest->getMetadataForDisplay(null, "; ", 0)[0]["label"]);
        self::assertEquals("No HTML", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_NO_TAGS)[0]["label"]);
        self::assertEquals("No HTML", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)[0]["label"]);
        self::assertEquals("No HTML", $manifest->getMetadataForDisplay()[0]["label"]);

        self::assertEquals("A text without tags but with < and >", $manifest->getMetadataForDisplay(null, "; ", 0)[0]["value"]);
        self::assertEquals("A text without tags but with < and >", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_NO_TAGS)[0]["value"]);
        self::assertEquals("A text without tags but with &lt; and &gt;", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)[0]["value"]);
        self::assertEquals("A text without tags but with &lt; and &gt;", $manifest->getMetadataForDisplay()[0]["value"]);
        
        self::assertEquals("Simple HTML", $manifest->getMetadataForDisplay(null, "; ", 0)[1]["label"]);
        self::assertEquals("Simple HTML", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_NO_TAGS)[1]["label"]);
        self::assertEquals("Simple HTML", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)[1]["label"]);
        self::assertEquals("Simple HTML", $manifest->getMetadataForDisplay()[1]["label"]);

        self::assertEquals("<span>Some HTML.  <img alt=\"It has images\" src=\"withsources\"><a href=\"http://example.org\">and <b>bold</b> links</a><a>and forbidden hrefs</a>alert('And scripts.')</span>", $manifest->getMetadataForDisplay(null, "; ", 0)[1]["value"]);
        self::assertEquals("Some HTML.  and bold linksand forbidden hrefsalert('And scripts.')", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_NO_TAGS)[1]["value"]);
        self::assertEquals("&lt;span&gt;Some HTML.  &lt;img alt=&quot;It has images&quot; src=&quot;withsources&quot;&gt;&lt;a href=&quot;http://example.org&quot;&gt;and &lt;b&gt;bold&lt;/b&gt; links&lt;/a&gt;&lt;a&gt;and forbidden hrefs&lt;/a&gt;alert(&#039;And scripts.&#039;)&lt;/span&gt;", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)[1]["value"]);
        self::assertEquals("<span>Some HTML.  <img alt=\"It has images\" src=\"withsources\"><a href=\"http://example.org\">and <b>bold</b> links</a><a>and forbidden hrefs</a>alert('And scripts.')</span>", $manifest->getMetadataForDisplay()[1]["value"]);
        
        self::assertEquals("broken<  script  >", $manifest->getMetadataForDisplay(null, "; ", 0)[2]["value"]);
        self::assertEquals("broken<  script  >", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_NO_TAGS)[2]["value"]);
        self::assertEquals("broken&lt;  script  &gt;", $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)[2]["value"]);
        self::assertEquals("broken&lt;  script  &gt;", $manifest->getMetadataForDisplay()[2]["value"]);
        
        echo $manifest->getMetadataForDisplay(null, "; ", 0)[3]["value"]."\n";
        echo $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_NO_TAGS)[3]["value"]."\n";
        echo $manifest->getMetadataForDisplay(null, "; ", IiifResourceInterface::SANITIZE_XML_ENCODE_ALL)[3]["value"]."\n";
        echo $manifest->getMetadataForDisplay()[3]["value"]."\n";
        
        echo $manifest->getMetadataForDisplay()[4]["value"]."\n";
        echo $manifest->getMetadataForDisplay()[5]["value"]."\n";
        echo $manifest->getMetadataForDisplay()[6]["value"]."\n";
        
        
        //self::assertEquals("<span>Some HTML.  <img alt='It has images' src='with sources'><a href='http://example.org'>and <b>bold</b> links</a>and forbidden hrefsalert('And scripts.')</span>", $manifest->getMetadataForDisplay()[0]["value"]);
        
        self::markTestIncomplete("not yet implemented");
    }

    /**
     * Tests \Ubl\Iiif\Presentation\Common\Model\AbstractIiifEntity->registerResource()
     */
    public function testRegisterResources() {
        
        $manifest = IiifHelper::loadIiifResource(self::getFile("v2/definition-order.json"));
        
        self::assertInstanceOf(Manifest::class, $manifest);
        self::assertEquals(6, count($manifest->getDefaultCanvases()));
        self::assertEquals(5, count($manifest->getStructures()));
        self::assertEquals(1, count($manifest->getRootRanges()));
        self::assertEquals("http://example.org/iiif/id1/range/range0", $manifest->getRootRanges()[0]->getId());
        
        self::markTestIncomplete("implement");
    }

    public function testKeywordAliases() {
        $manifest = IiifHelper::loadIiifResource(self::getFile("common/manifest-with-keywordalias.json"));
        self::assertNotNull($manifest);
        self::assertInstanceOf(Manifest::class, $manifest);

        $canvases = $manifest->getDefaultCanvases();
        self::assertNotEmpty($canvases);
        foreach ($canvases as $canvas) {
            self::assertNotNull($canvas);
            self::assertInstanceOf(Canvas::class, $canvas);
            self::assertEquals(1, sizeof($canvas->getImageAnnotations()));
            
            $imageAnnotation = $canvas->getImageAnnotations()[0];

            self::assertNotNull($imageAnnotation);
            self::assertInstanceOf(Annotation::class, $imageAnnotation);
            self::assertNotEmpty($imageAnnotation->getId());

            self::assertNotNull($imageAnnotation->getBody());
            self::assertInstanceOf(ContentResource::class, $imageAnnotation->getBody());
            self::assertNotEmpty($imageAnnotation->getBody()->getId());
            self::assertEquals("image/jpeg", $imageAnnotation->getBody()->getFormat());

            self::assertNotNull($imageAnnotation->getBody()->getSingleService());
            self::assertInstanceOf(ImageInformation2::class, $imageAnnotation->getBody()->getSingleService());
            self::assertNotEmpty($imageAnnotation->getBody()->getSingleService()->getId());
        }
    }
    
}

