<?php
namespace iiif\context;

class IRI
{
    /**
     * IRI regex with named groups. See https://tools.ietf.org/html/rfc3986#appendix-B
     */
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
    
    public static function isCompactUri($uri) {
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
    
    public static function isAbsoluteIri($uri) {
        return self::isUri($uri) && !empty((new IRI($uri))->scheme);
    }
    
    public static function isRelativeIri($uri) {
        return self::isUri($uri) && empty((new IRI($uri))->scheme);
    }
    
    /**
     * See https://tools.ietf.org/html/rfc3986#section-5.2
     * @param string $baseIri
     * @param string $relativeIri
     * @return string
     */
    public static function resolveAbsoluteIri(string $baseIri, string $relativeIri) {
        $base = new IRI($baseIri);
        $rel = new IRI($relativeIri);
        if (!empty($rel->getScheme())) {
            $schema = $rel->getScheme();
            $authority = $rel->getAuthority();
            $path = self::removeDotSegments($rel->getPath());
            $query = $rel->getQuery();
        } else {
            if (!empty($rel->getAuthority())) {
                $authority = $rel->getAuthority();
                $path = self::removeDotSegments($rel->getPath());
                $query = $rel->getQuery();
            } else {
                if (empty($rel->getPath())) {
                    $path = $base->getPath();
                    if (!empty($rel->getQuery())) {
                        $query = $rel->getQuery();
                    } else {
                        $query = $base->getQuery();
                    }
                } else {
                    if (strpos($rel->getPath(), "/")===0) {
                        $path = self::removeDotSegments($rel->getPath());
                    } else {
                        if (!empty($base->getAuthority()) && empty($base->getPath())) {
                            $path = (strpos($rel->getPath(), "/")===0?"":"/").$rel->getPath();
                        } else {
                            $relPath = strpos($rel->getPath(), "/")===0?substr($rel->getPath(), 1):$rel->getPath();
                            $path = strpos($base->getPath(), "/")===false?$rel->getPath():(substr($base->getPath(), 0, strrpos($base->getPath(), "/")+1).$relPath);
                        }
                        $path = self::removeDotSegments($path);
                    }
                    $query = $rel->getQuery();
                }
                $authority = $base->getAuthority();
            }
            $schema = $base->getScheme();
        }
        $fragment = $rel->getFragment();
        return (empty($schema)?"":($schema.":")).$authority.$path.(empty($query)?"":"?".$query).(empty($fragment)?"":("#".$fragment));
    }
    
    /**
     * See https://tools.ietf.org/html/rfc3986#section-5.2.4
     * 
     * @param string $path
     * @return string
     */
    private static function removeDotSegments(string $path) {
        $input = $path;
        $output = "";
        while (!empty($input)) {
            if (strpos($input, "../")===0){
                $input = substr($input, 3);
            } elseif (strpos($input, "./")===0) {
                $input = substr($input, 2);
            } elseif (strpos($input, "/./")===0) {
                $input = substr($input, 2);
            } elseif ($input=="/.") {
                $input = "/".substr($input, 2);
            } elseif (strpos($input, "/../")===0) {
                $input = substr($input, 3);
                $outArray=explode("/",$output);
                $segment = empty($outArray[count($outArray)-1])?$outArray[count($outArray)-2]."/":$outArray[count($outArray)-1];
                $output = substr($output, 0, strlen($output)-strlen($segment));
                if (strrpos($output, "/") == ($length=strlen($output)-1) && $output != "/") {
                    $output = substr($output, 0, $length);
                }
            } elseif (strpos($input, "/..")===0) {
                $input = "/".substr($input, 3);
            } elseif ($input=="." || $input=="..") {
                $input="";
            } else {
                $inArray=explode("/",$input);
                $segment = empty($inArray[0])?"/".$inArray[1]:$inArray[0];
                $output .= $segment;
                $input = substr($input, strlen($segment));
            }
        }
        return $output;
    }
    
    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @return mixed
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @return mixed
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @return mixed
     */
    public function getFragment()
    {
        return $this->fragment;
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
