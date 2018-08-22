<?php
use iiif\context\JsonLdProcessor;
use iiif\context\JsonLdContext;

require_once 'iiif/context/JsonLdProcessor.php';

/**
 * JsonLdProcessor test case.
 */
class JsonLdProcessorTest extends PHPUnit_Framework_TestCase
{

    /**
     * Tests JsonLdProcessor::processContext()
     */
    public function testProcessContext()
    {
        $helper = new JsonLdProcessor();
        $context = new JsonLdContext();
        $context->setBaseIri("http://iiif.io/api/presentation/3/context.json");
        $processedContext = $helper->processContext(["http://iiif.io/api/presentation/3/context.json"], $context);
        
        $this->markTestIncomplete("loadJsonLdContext test not implemented");
    }

    /**
     * Tests JsonLdProcessor::isSequentialArray()
     */
    public function testIsSequentialArray()
    {
        $sequential1 = ['1', 'a', 'x'];
        $sequential2 = [0 => '1', 1 => 'a', 2 => 'x'];
        $nonSequential1 = [0 => '1', -1 => 'a', 2 => 'x'];
        $nonSequential2 = ['I' => '1', 0 => 'a', 1 => 'x'];
        
        self::assertTrue(JsonLdProcessor::isSequentialArray($sequential1));
        self::assertTrue(JsonLdProcessor::isSequentialArray($sequential2));
        self::assertFalse(JsonLdProcessor::isSequentialArray($nonSequential1));
        self::assertFalse(JsonLdProcessor::isSequentialArray($nonSequential2));
    }
}

