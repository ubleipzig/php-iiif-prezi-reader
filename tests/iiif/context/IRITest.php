<?php
namespace iiif\context;


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
            "authority" => "//userinfo@host:port",
            "path" => "/path",
            "query" => "query",
            "fragment" => "fragment"
        ],
        [
            "iri" => "scheme://userinfo@host:port/path/morepath?queryparam=value&p=v#fragment",
            "scheme" => "scheme",
            "authority" => "//userinfo@host:port",
            "path" => "/path/morepath",
            "query" => "queryparam=value&p=v",
            "fragment" => "fragment"
        ],
    ];
    
    public function testConstruct() {
        $data = $this->expectations[1];
        $byIri = new IRI($data["iri"]);
        foreach ($data as $key => $value) {
            self::assertEquals($value, $byIri->$key, $key." in ".$data["iri"]." should be ".$value.", is ".$byIri->$key);
        }
        
        $byObject = new IRI($byIri);
        foreach ($data as $key => $value) {
            self::assertEquals($value, $byObject->$key, $key." in ".$data["iri"]." should be ".$value.", is ".$byObject->$key);
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
                if ($key == "iri") continue;
                self::assertEquals($value, $matches[$key], $key." in ".$array["iri"]." should be ".$value.", is ".$matches[$key]);
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

}

