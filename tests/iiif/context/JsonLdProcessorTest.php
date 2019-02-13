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

use iiif\context\JsonLdProcessor;
use iiif\context\JsonLdContext;
use iiif\context\JsonLdHelper;
use PHPUnit\Framework\TestCase;

/**
 * JsonLdProcessor test case.
 */
class JsonLdProcessorTest extends TestCase
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

