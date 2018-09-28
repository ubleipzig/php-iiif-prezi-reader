<?php
use iiif\context\Keywords;

require_once 'iiif/context/Keywords.php';

/**
 * Keywords test case.
 */
class KeywordsTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests Keywords::isKeyword()
     */
    public function testIsKeyword()
    {
        self::assertTrue(Keywords::isKeyword("@base"));
        self::assertTrue(Keywords::isKeyword("@id"));
        self::assertFalse(Keywords::isKeyword("@notakeyword"));
        self::assertFalse(Keywords::isKeyword(null));
        self::assertFalse(Keywords::isKeyword("foo"));
    }
}

