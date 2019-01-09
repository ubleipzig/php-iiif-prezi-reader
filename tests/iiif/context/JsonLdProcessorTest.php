<?php
use iiif\context\JsonLdProcessor;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdHelper;

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
        $context = new JsonLdContext($helper);
        $context->setBaseIri("http://iiif.io/api/presentation/3/context.json");
        $processedContext = $helper->processContext(["http://iiif.io/api/presentation/3/context.json"], $context);
        $widthTerm = $processedContext->getTermDefinition("width");
        self::assertEquals("http://www.w3.org/2001/XMLSchema#integer", $widthTerm->getTypeMapping());
        
        $this->markTestIncomplete("loadJsonLdContext test not implemented");
    }

    /**
     * Tests JsonLdHelper::isSequentialArray()
     */
    public function testIsSequentialArray()
    {
        $sequential1 = ['1', 'a', 'x'];
        $sequential2 = [0 => '1', 1 => 'a', 2 => 'x'];
        $nonSequential1 = [0 => '1', -1 => 'a', 2 => 'x'];
        $nonSequential2 = ['I' => '1', 0 => 'a', 1 => 'x'];
        
        self::assertTrue(JsonLdHelper::isSequentialArray($sequential1));
        self::assertTrue(JsonLdHelper::isSequentialArray($sequential2));
        self::assertFalse(JsonLdHelper::isSequentialArray($nonSequential1));
        self::assertFalse(JsonLdHelper::isSequentialArray($nonSequential2));
    }
}

