<?php
use iiif\context\JsonLdContextHelper;

require_once 'iiif/context/JsonLdContextHelper.php';

/**
 * JsonLdContextHelper test case.
 */
class JsonLdContextHelperTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests JsonLdContextHelper::loadJsonLdContext()
     */
    public function testLoadJsonLdContext()
    {
        // TODO Auto-generated JsonLdContextHelperTest::testLoadJsonLdContext()
        $this->markTestIncomplete("loadJsonLdContext test not implemented");
        
        JsonLdContextHelper::loadJsonLdContext(/* parameters */);
    }

    /**
     * Tests JsonLdContextHelper::isSequentialArray()
     */
    public function testIsSequentialArray()
    {
        $sequential1 = ['1', 'a', 'x'];
        $sequential2 = [0 => '1', 1 => 'a', 2 => 'x'];
        $notSequential1 = [0 => '1', -1 => 'a', 2 => 'x'];
        $notSequential2 = ['I' => '1', 0 => 'a', 1 => 'x'];
        
        self::assertTrue(JsonLdContextHelper::isSequentialArray($sequential1));
        self::assertTrue(JsonLdContextHelper::isSequentialArray($sequential2));
        self::assertFalse(JsonLdContextHelper::isSequentialArray($notSequential1));
        self::assertFalse(JsonLdContextHelper::isSequentialArray($notSequential2));
    }
}

