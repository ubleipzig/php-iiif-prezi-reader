<?php

namespace Ubl\Iiif\Tools;

use PHPUnit\Framework\TestCase;

class IiifHtmlSanitizerTest extends TestCase
{
    public function testSanitizeHtml(): void
    {
        IiifHtmlSanitizer::sanitizeHtml("");

        self::assertEquals("No HTML", IiifHtmlSanitizer::sanitizeHtml("No HTML"));
        // self::assertEquals("A text without tags but with < and >", IiifHtmlSanitizer::sanitizeHtml("A text without tags but with < and >"));
        self::assertEquals("something <b>bold</b> or <i>italic</i> or <small>small</small> or <sub>low</sub> or <sup>high</sup>",
            IiifHtmlSanitizer::sanitizeHtml("something <b>bold</b> or <i>italic</i> or <small>small</small> or <sub>low</sub> or <sup>high</sup>"));
        self::assertEquals("An <a href=\"https://example.test\">HTTPS link</a>", IiifHtmlSanitizer::sanitizeHtml("An <a href=\"https://example.test\">HTTPS link</a>"));
        self::assertEquals("An <a href=\"http://example.test\">HTTP link</a>", IiifHtmlSanitizer::sanitizeHtml("An <a href=\"http://example.test\">HTTP link</a>"));
        self::assertEquals("An <a>FTP link</a>", IiifHtmlSanitizer::sanitizeHtml("An <a href=\"ftp://example.test\">FTP link</a>"));
        self::assertEquals("A <a href=\"mailto:test&#64;test.test\">mailto link</a>", IiifHtmlSanitizer::sanitizeHtml("A <a href=\"mailto:test@test.test\">mailto link</a>"));
        self::assertEquals("An <img src=\"http://example.test/img.jpg\" alt=\"image with source, alt text and unwanted attributes\" />",
            IiifHtmlSanitizer::sanitizeHtml("An <img src=\"http://example.test/img.jpg\" alt=\"image with source, alt text and unwanted attributes\" onerror=\"alert('Oh no!')\"/>"));

        self::assertEquals(
            "<span>Some HTML.  <img alt=\"It has images\" /><a href=\"http://example.org\">and <b>bold</b> links</a><a>and forbidden hrefs</a></span>",
            IiifHtmlSanitizer::sanitizeHtml("<span>Some HTML.  <img alt='It has images' src='withsources'><a href='http://example.org' target='blank'>and <b>bold</b> links</a><a href='urn:example'>and forbidden hrefs</a><script>alert('And scripts.')</script><!-- and comments--></span>"));
        $this->markTestIncomplete("add some more assertions");
    }
}
