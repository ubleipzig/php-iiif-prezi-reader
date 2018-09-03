<?php
namespace iiif\context;


require_once 'iiif/context/IRI.php';

/**
 * IRI test case.
 */
class IRITest extends \PHPUnit_Framework_TestCase
{
    protected $expectations = [
        [
            "uri" => "scheme://userinfo@host:port/path?query#fragment",
            "scheme" => "scheme",
            "authority" => "//userinfo@host:port",
            "path" => "/path",
            "query" => "query",
            "fragment" => "fragment"
        ],
        [
            "uri" => "scheme://userinfo@host:port/path/morepath?queryparam=value&p=v#fragment",
            "scheme" => "scheme",
            "authority" => "//userinfo@host:port",
            "path" => "/path/morepath",
            "query" => "queryparam=value&p=v",
            "fragment" => "fragment"
        ],
    ];
    
    public function testConstruct() {
        $data = $this->expectations[1];
        $byUri = new IRI($data["uri"]);
        foreach ($data as $key => $value) {
            self::assertEquals($value, $byUri->$key, $key." in ".$data["uri"]." should be ".$value.", is ".$byUri->$key);
        }
        
        $byObject = new IRI($byUri);
        foreach ($data as $key => $value) {
            self::assertEquals($value, $byObject->$key, $key." in ".$data["uri"]." should be ".$value.", is ".$byObject->$key);
        }
        
        $empty = new IRI();
        foreach ($data as $key => $value) {
            self::assertNull($empty->$key, $key." in ".$data["uri"]." should be null, is ".$empty->$key);
        }
    }
    
    
    public function testUriRegex() {
        foreach ($this->expectations as $array) {
            $matches = array();
            $found = preg_match(IRI::URI_REGEX, $array["uri"], $matches);
            self::assertEquals(1, $found, $array["uri"].' does not match regex');
            foreach ($array as $key => $value) {
                if ($key == "uri") continue;
                self::assertEquals($value, $matches[$key], $key." in ".$array["uri"]." should be ".$value.", is ".$matches[$key]);
            }
        }
    }
    
    public function testIsCompressedUri() {
        self::assertFalse(IRI::isCompactUri(null));
        self::assertFalse(IRI::isCompactUri(""));
        self::assertFalse(IRI::isCompactUri("path"));
        self::assertTrue(IRI::isCompactUri("ns:path"));
        self::assertFalse(IRI::isCompactUri("ssh://root@127.0.0.1/"));
        self::assertFalse(IRI::isCompactUri("schema://host"));
        self::assertFalse(IRI::isCompactUri("http://iiif.io/api/presentation/3/context.json"));
    }
    
    public function testIsUri() {
        self::assertFalse(IRI::isUri(null));
        self::assertFalse(IRI::isUri(""));
        self::assertTrue(IRI::isUri("path"));
        self::assertTrue(IRI::isUri("ns:path"));
        self::assertTrue(IRI::isUri("ssh://root@127.0.0.1/"));
        self::assertTrue(IRI::isUri("schema://host"));
        self::assertTrue(IRI::isUri("http://iiif.io/api/presentation/3/context.json"));
    }
    
    public function testIsExpandedUri() {
        self::assertFalse(IRI::isExpandedUri(null));
        self::assertFalse(IRI::isExpandedUri(""));
        self::assertFalse(IRI::isExpandedUri("path"));
        self::assertFalse(IRI::isExpandedUri("ns:path"));
        self::assertTrue(IRI::isExpandedUri("ssh://root@127.0.0.1/"));
        self::assertTrue(IRI::isExpandedUri("schema://host"));
        self::assertTrue(IRI::isExpandedUri("http://iiif.io/api/presentation/3/context.json"));
    }
    

}

