<?php
namespace iiif\context;

class IRI
{
    CONST URI_REGEX = '_^((?P<scheme>[^:/?#]+):)?(?P<authority>(//)([^/?#]*))?(?P<path>[^?#]*)(\?(?P<query>[^#]*))?(#(?P<fragment>.*))?_';
    
    CONST COMPRESS_URI_REGEX = "_^(?P<namespace>[^:/?#]+):(?P<term>[^:/?#]+)_";
    
    protected $uri;
    protected $scheme;
    protected $authority;
    protected $userInfo;
    protected $host;
    protected $port;
    protected $path;
    protected $query;
    protected $fragment;
    
    public function __construct($iri=null) {
        if (is_string($iri) && self::isUri($iri)) {
            $this->uri = $iri;
            $found = preg_match(self::URI_REGEX, $iri, $matches);
            foreach ($matches as $key => $value) {
                $this->$key = $value;
            }
        } elseif ($iri instanceof IRI) {
            $this->uri = $iri->uri;
            $this->scheme = $iri->scheme;
            $this->authority = $iri->authority;
            $this->userInfo = $iri->userInfo;
            $this->host = $iri->host;
            $this->port = $iri->port;
            $this->path = $iri->path;
            $this->query = $iri->query;
            $this->fragment = $iri->fragment;
        }
    }
    
    public static function isCompressedUri($uri) {
        return preg_match(IRI::COMPRESS_URI_REGEX, $uri)===1;
    }
    
    public static function isUri($uri) {
        if ($uri == null) return false;
        return preg_match(IRI::URI_REGEX, $uri)===1;
    }

    public static function isExpandedUri($uri) {
        $matches = array();
        $result = preg_match(IRI::URI_REGEX, $uri, $matches);
        return $result===1 && !empty($matches["scheme"]) && !empty($matches["authority"]);
    }
    
    /**
     * Use for tests only!
     * @param mixed $field
     * @return mixed
     */
    public function __get($field) {
        return $this->$field;
    }
}
