<?php

use PHPUnit\Framework\TestCase;

/**
 * StaticCodeTest test case.
 */
class StaticCodeTest extends TestCase {
    
    public function testInclude() {
        require_once __DIR__.'/../../src/iiif/include.php';
        self::assertTrue(class_exists('\\iiif\\tools\\IiifHelper', false));
    }

}