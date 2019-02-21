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

namespace Ubl\Iiif\Context;


use PHPUnit\Framework\TestCase;

/**
 * IRI test case.
 */
class IRITest extends TestCase
{
    protected $expectations = [
        [
            "iri" => "scheme://userinfo@host:port/path?query#fragment",
            "scheme" => "scheme",
            "doubleSlash" => "//",
            "authority" => "userinfo@host:port",
            "userInfo" => "userinfo",
            "host" => "host:port",
            "port" => null,
            "path" => "/path",
            "query" => "query",
            "fragment" => "fragment"
        ],
        [
            "iri" => "scheme://userinfo@host:1234/path/morepath?queryparam=value&p=v#fragment",
            "scheme" => "scheme",
            "doubleSlash" => "//",
            "authority" => "userinfo@host:1234",
            "userInfo" => "userinfo",
            "host" => "host",
            "port" => "1234",
            "path" => "/path/morepath",
            "query" => "queryparam=value&p=v",
            "fragment" => "fragment"
        ],
        [
            "iri" => "tel:+493410000000",
            "scheme" => "tel",
            "doubleSlash" => "",
            "authority" => "",
            "userInfo" => "",
            "host" => null,
            "port" => null,
            "path" => "+493410000000",
            "query" => null,
            "fragment" => null
        ],
        [
            "iri" => "http://93.184.216.34:80/example",
            "scheme" => "http",
            "doubleSlash" => "//",
            "authority" => "93.184.216.34:80",
            "userInfo" => null,
            "host" => "93.184.216.34",
            "port" => "80",
            "path" => "/example",
            "query" => null,
            "fragment" => null
        ],
        [
            "iri" => "https://user:password@[2001:0db8:85a3:08d3::0370:7344]/?p=1",
            "scheme" => "https",
            "doubleSlash" => "//",
            "authority" => "user:password@[2001:0db8:85a3:08d3::0370:7344]",
            "userInfo" => "user:password",
            "host" => "[2001:0db8:85a3:08d3::0370:7344]",
            "port" => null,
            "path" => "/",
            "query" => "p=1",
            "fragment" => null
        ],
        [
            "iri" => "http://www.\u{1F648}\u{1F649}\u{1F64A}.info:8080/#anchor",
            "scheme" => "http",
            "doubleSlash" => "//",
            "authority" => "www.\u{1F648}\u{1F649}\u{1F64A}.info:8080",
            "userInfo" => null,
            "host" => "www.\u{1F648}\u{1F649}\u{1F64A}.info",
            "port" => "8080",
            "path" => "/",
            "query" => "",
            "fragment" => "anchor"
        ]
        
        
        
    ];
    
    public function testConstruct() {
        foreach ($this->expectations as $data) {
            $byIri = new IRI($data["iri"]);
            foreach ($data as $key => $value) {
                self::assertEquals($value, $byIri->$key, $key." in ".$data["iri"]." should be ".$value.", is ".$byIri->$key);
            }
            
            $byObject = new IRI($byIri);
            foreach ($data as $key => $value) {
                self::assertEquals($value, $byObject->$key, $key." in ".$data["iri"]." should be ".$value.", is ".$byObject->$key);
            }
        }
        $empty = new IRI();
        foreach ($data as $key => $value) {
            self::assertNull($empty->$key, $key." in ".$data["iri"]." should be null, is ".$empty->$key);
        }
    }
    
    /**
     * Test if the named groups for the IRI regex do actual work as expected.
     * 
     */
    public function testIriRegex() {
        foreach ($this->expectations as $array) {
            $matches = array();
            $found = preg_match(IRI::IRI_REGEX, $array["iri"], $matches);
            self::assertEquals(1, $found, $array["iri"].' does not match regex');
            foreach ($array as $key => $value) {
                if (array_search($key, ["iri", "userInfo", "host", "port"]) !== false) {
                    continue;
                }
                if (isset($value)) {
                    self::assertEquals($value, $matches[$key], $key." in ".$array["iri"]." should be ".$value.", is ".$matches[$key]);
                } else {
                    self::assertFalse(array_key_exists($key, $matches), "Unexpected ".$key." in ".$array["iri"]);
                }
            }
        }
    }
    
    /**
     * Tests IRI::isCompactIri()
     */
    public function testIsCompactIri() {
        $processor = new JsonLdProcessor();
        $context = $processor->processContext(json_decode('{"schema":"http://www.example.org/schema#", "üöä":"http://example.org/vocab"}', true), new JsonLdContext($processor));
        self::assertFalse(IRI::isCompactIri(null, $context));
        self::assertFalse(IRI::isCompactIri("", $context));
        self::assertFalse(IRI::isCompactIri("path", $context));
        self::assertFalse(IRI::isCompactIri("schema:", $context));
        self::assertTrue(IRI::isCompactIri("schema:path", $context));
        self::assertTrue(IRI::isCompactIri("schema:öäü", $context));
        self::assertTrue(IRI::isCompactIri("üöä:öäü", $context));
        self::assertFalse(IRI::isCompactIri("urn:ISBN:3-8273-7019-1", $context));
        self::assertFalse(IRI::isCompactIri("ssh://root@127.0.0.1/", $context));
        self::assertFalse(IRI::isCompactIri("schema://host", $context));
        self::assertFalse(IRI::isCompactIri("http://iiif.io/api/presentation/3/context.json", $context));
        
        $processor = new JsonLdProcessor();
        $context = $processor->processContext(json_decode('{"urn":"http://www.example.org/override-scheme"}', true), new JsonLdContext($processor));
        self::assertTrue(IRI::isCompactIri("urn:ISBN:3-8273-7019-1", $context));
        
    }
    
    /**
     * Tests IRI::isIri()
     */
    public function testIsIri() {
        self::assertFalse(IRI::isIri(null));
        self::assertFalse(IRI::isIri(""));
        self::assertTrue(IRI::isIri("path"));
        self::assertTrue(IRI::isIri("schema:path"));
        self::assertTrue(IRI::isIri("http://öäüéè.example.org"));
        self::assertTrue(IRI::isIri("ssh://root@127.0.0.1/"));
        self::assertTrue(IRI::isIri("urn:ISBN:3-8273-7019-1"));
        self::assertTrue(IRI::isIri("schema://host"));
        self::assertTrue(IRI::isIri("http://iiif.io/api/presentation/3/context.json"));
    }
    
    /**
     * Tests IRI::isAbsoluteIri()
     */
    public function testIsAbsoluteIri() {
        self::assertTrue(IRI::isAbsoluteIri("http://iiif.io/api/presentation/3/context.json"));
        self::assertTrue(IRI::isAbsoluteIri("urn:ISBN:3-8273-7019-1"));
        self::assertFalse(IRI::isAbsoluteIri('{"jsonkey":"jsonvalue"}'));
    }

    public function testIsRelativeIri() {
        self::assertFalse(IRI::isRelativeIri(""));
        self::assertFalse(IRI::isRelativeIri("http://localhost"));
        self::assertFalse(IRI::isRelativeIri("tel:115"));
        self::assertFalse(IRI::isRelativeIri("urn:ISBN:3-8273-7019-1"));
        self::assertFalse(IRI::isRelativeIri('{"jsonkey":"jsonvalue"}'));
        self::assertTrue(IRI::isRelativeIri("abc"));
        self::assertTrue(IRI::isRelativeIri("/#fragment"));
        self::assertTrue(IRI::isRelativeIri("/ab"));
        self::assertTrue(IRI::isRelativeIri("115"));
    }
    
    public function testResolveAbsoluteIri() {
        $base = "http://example.org/somepath/a/b/c/";
        self::assertEquals("http://example.org/somepath/a/b/c/x/y/z", IRI::resolveAbsoluteIri($base, "x/y/z"));
        self::assertEquals("http://example.org/x/y/z", IRI::resolveAbsoluteIri("http://example.org", "x/y/z"));
        self::assertEquals("http://example.org/x/y/z", IRI::resolveAbsoluteIri("http://example.org", "/x/y/z"));
        self::assertEquals("http://example.org/x/y/z", IRI::resolveAbsoluteIri($base, "/x/y/z"));
        self::assertEquals("http://example.org/y/z", IRI::resolveAbsoluteIri($base, "/x/../y/z"));
        self::assertEquals("http://example.org/somepath/a/b/c/y/z", IRI::resolveAbsoluteIri($base, "x/../y/z"));
        self::assertEquals("http://example.org/somepath/a/b/x/y/z", IRI::resolveAbsoluteIri($base, "../x/y/z"));
        self::assertEquals("http://example.org/somepath/a/x/y/z", IRI::resolveAbsoluteIri($base, "./../././../x/y/z"));
        self::assertEquals("http://example.org/x/y/z", IRI::resolveAbsoluteIri($base, "../../../../../.././../../x/y/z"));
        self::assertEquals("http://example.org/somepath/a/b/c/x/y/z", IRI::resolveAbsoluteIri($base."#fragment", "x/y/z"));
        self::assertEquals("http://example.org/somepath/a/b/c/x/y/z#fragment", IRI::resolveAbsoluteIri($base, "x/y/z#fragment"));
        self::assertEquals("http://example.org/x/y/z#fragment", IRI::resolveAbsoluteIri($base, "//example.org/x/y/z#fragment"));
        self::assertEquals("http://example.org/somepath/a/b/c/x/y/z?query#fragment", IRI::resolveAbsoluteIri($base, "x/y/z?query#fragment"));
        self::assertEquals("http://example.org/somepath/a/b/c/?query#fragment", IRI::resolveAbsoluteIri($base, "?query#fragment"));
        self::assertEquals("http://example.org/somepath/a/b/c/#fragment", IRI::resolveAbsoluteIri($base, "#fragment"));
        self::assertEquals("http://example.org/somepath/a/b/c/?query#fragment", IRI::resolveAbsoluteIri($base."?query", "#fragment"));
        self::assertEquals("http://example.org/somepath/a/b/c?query#fragment", IRI::resolveAbsoluteIri("http://example.org/somepath/a/b/c?query#absfrag", "#fragment"));
        self::assertEquals("tel:+115", IRI::resolveAbsoluteIri("tel:+110", "+115"));
        self::assertEquals("fax:+115", IRI::resolveAbsoluteIri("tel:+110", "fax:+115"));
        self::assertEquals("a/.hidden/", IRI::resolveAbsoluteIri("", "../.././a/.hidden/."));
        self::assertEquals("a/..weird/", IRI::resolveAbsoluteIri("", "../.././a/..weird/."));
        self::assertEquals("a/", IRI::resolveAbsoluteIri("", "../.././a/./."));
        self::assertEquals("/", IRI::resolveAbsoluteIri("", "../.././a/././.."));
        self::assertEquals("a", IRI::resolveAbsoluteIri("", "./a"));
        self::assertEquals("", IRI::resolveAbsoluteIri("", "./.."));
        self::assertEquals("", IRI::resolveAbsoluteIri("", "./."));
        self::assertEquals("/a/g", IRI::resolveAbsoluteIri("", "/a/b/c/./../../g"));
        self::assertEquals("mid/6", IRI::resolveAbsoluteIri("", "mid/content=5/../6"));
    }
    
    public function testGetters() {
        foreach ($this->expectations as $data) {
            $iri = new IRI($data["iri"]);
            foreach ($data as $key => $value) {
                $func = "get".ucfirst($key);
                self::assertEquals($value, call_user_func(array($iri, $func)));
            }
        }
    }
    
    public function test__GetFails() {
        $iri = new IRI("http://example.org");
        self::assertNull($iri->iri);
    }

}
