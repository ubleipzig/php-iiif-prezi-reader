<?php
use iiif\context\Keywords;
use PHPUnit\Framework\TestCase;

/**
 * Keywords test case.
 */
class KeywordsTest extends TestCase
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

