<?php

use iiif\presentation\common\model\resources\IiifResourceInterface;
use iiif\tools\IiifHelper;
use iiif\AbstractIiifTest;
use iiif\presentation\v2\model\resources\Manifest;

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
        
        $doc = parent::getJson("manifest-html-metadata.json");
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
}

