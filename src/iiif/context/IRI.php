<?php
namespace iiif\context;

/**
 * Represents an Internationalized Resource Identifier and offers several methods for information about a given IRI in the context of JSON-LD.
 *
 * @author lutzhelm
 *
 */
class IRI {

    /**
     * IRI regex with named groups.
     * @link https://tools.ietf.org/html/rfc3986#appendix-B
     */
    const URI_REGEX = '_^((?P<scheme>[^:/?#]+):)?(?P<authority>(//)([^/?#]*))?(?P<path>[^?#]*)(\?(?P<query>[^#]*))?(#(?P<fragment>.*))?_';

    const COMPACT_URI_REGEX = "_^(?P<prefix>[^:/?#]+):(?P<term>[^:/?#]+)_";

    /**
     * @var string
     */
    protected $uri;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $authority;

    /**
     * @var string
     */
    protected $userInfo;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var string
     */
    protected $port;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var string
     */
    protected $fragment;

    public function __construct($iri = null) {
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

    /**
     * Checks if a given string is a compact URI. It therefore has to have a prefix and a term definition for the prefix in the current JSON-LD context. 
     * @param string $uri
     * @param JsonLdContext $context
     * @return boolean
     */
    public static function isCompactUri($uri, JsonLdContext $context) {
        if (!preg_match(self::COMPACT_URI_REGEX, $uri)) {
            return false;
        }
        preg_match(self::COMPACT_URI_REGEX, $uri, $matches);
        $prefix = $matches["prefix"];
        return !empty($prefix) && $context->getTermDefinition($prefix) != null && !empty($context->getTermDefinition($prefix)->getIriMapping());
    }

    /**
     * Checks if a string has the form of a URI by matching the regex in https://tools.ietf.org/html/rfc3986#appendix-B
     * @param string $uri
     * @return boolean
     * @link https://tools.ietf.org/html/rfc3986#appendix-B
     */
    public static function isUri($uri) {
        if ($uri == null)
            return false;
        return preg_match(IRI::URI_REGEX, $uri) === 1;
    }

    /**
     * 
     * @param string $uri
     * @return boolean
     * @deprecated A URI is only expanded or compacted in the context of a JSON-LD context. Use isCompactUri() instead.
     */
    public static function isExpandedUri($uri) {
        $matches = array();
        $result = preg_match(IRI::URI_REGEX, $uri, $matches);
        return $result === 1 && ! empty($matches["scheme"]) && ! empty($matches["authority"]);
    }

    /**
     * Checks if a URI is an absolute URI for JSON-LD purposes.
     * From https://www.w3.org/TR/json-ld11-api/#dfn-absolute-iri :
     * "An absolute IRI is defined in [RFC3987] containing a scheme along with a path and optional query and fragment segments."
     *  
     * @param string $uri
     * @return boolean true
     */
    public static function isAbsoluteIri($uri) {
        $iri = new IRI($uri);
        $absoluteIri = self::isUri($uri) && ! empty($iri->scheme) && ! empty($iri->path) && ! (strpos(trim($uri), "{") === 0 && strrpos(trim($uri), "}") === strlen(trim($uri))-1);
        return self::isUri($uri) && ! empty($iri->scheme) && ! empty($iri->path) && ! (strpos(trim($uri), "{") === 0 && strrpos(trim($uri), "}") === strlen(trim($uri))-1);
    }

    /**
     * Checks if a URI is a relative URI. For that purpose we check if it matches the URI regex and misses a scheme.
     * @param string $uri
     * @return boolean true if $uri has the form of a relative URI, otherwise false
     */
    public static function isRelativeIri($uri) {
        return self::isUri($uri) && empty((new IRI($uri))->scheme);
    }

    /**
     * @param string $baseIri
     * @param string $relativeIri
     * @return string
     * @link https://tools.ietf.org/html/rfc3986#section-5.2
     */
    public static function resolveAbsoluteIri($baseIri, $relativeIri) {
        $base = new IRI($baseIri);
        $rel = new IRI($relativeIri);
        if (! empty($rel->getScheme())) {
            $schema = $rel->getScheme();
            $authority = $rel->getAuthority();
            $path = self::removeDotSegments($rel->getPath());
            $query = $rel->getQuery();
        } else {
            if (! empty($rel->getAuthority())) {
                $authority = $rel->getAuthority();
                $path = self::removeDotSegments($rel->getPath());
                $query = $rel->getQuery();
            } else {
                if (empty($rel->getPath())) {
                    $path = $base->getPath();
                    if (! empty($rel->getQuery())) {
                        $query = $rel->getQuery();
                    } else {
                        $query = $base->getQuery();
                    }
                } else {
                    if (strpos($rel->getPath(), "/") === 0) {
                        $path = self::removeDotSegments($rel->getPath());
                    } else {
                        if (! empty($base->getAuthority()) && empty($base->getPath())) {
                            $path = (strpos($rel->getPath(), "/") === 0 ? "" : "/") . $rel->getPath();
                        } else {
                            $relPath = strpos($rel->getPath(), "/") === 0 ? substr($rel->getPath(), 1) : $rel->getPath();
                            $path = strpos($base->getPath(), "/") === false ? $rel->getPath() : (substr($base->getPath(), 0, strrpos($base->getPath(), "/") + 1) . $relPath);
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
        return (empty($schema) ? "" : ($schema . ":")) . $authority . $path . (empty($query) ? "" : "?" . $query) . (empty($fragment) ? "" : ("#" . $fragment));
    }

    /**
     * @param string $path
     * @return string
     * @link https://tools.ietf.org/html/rfc3986#section-5.2.4
     */
    private static function removeDotSegments($path) {
        $input = $path;
        $output = "";
        while (! empty($input)) {
            if (strpos($input, "../") === 0) {
                $input = substr($input, 3);
            } elseif (strpos($input, "./") === 0) {
                $input = substr($input, 2);
            } elseif (strpos($input, "/./") === 0) {
                $input = substr($input, 2);
            } elseif ($input == "/.") {
                $input = "/" . substr($input, 2);
            } elseif (strpos($input, "/../") === 0) {
                $input = substr($input, 3);
                $outArray = explode("/", $output);
                $segment = empty($outArray[count($outArray) - 1]) ? $outArray[count($outArray) - 2] . "/" : $outArray[count($outArray) - 1];
                $output = substr($output, 0, strlen($output) - strlen($segment));
                if (strrpos($output, "/") == ($length = strlen($output) - 1) && $output != "/") {
                    $output = substr($output, 0, $length);
                }
            } elseif (strpos($input, "/..") === 0) {
                $input = "/" . substr($input, 3);
            } elseif ($input == "." || $input == "..") {
                $input = "";
            } else {
                $inArray = explode("/", $input);
                $segment = empty($inArray[0]) ? "/" . $inArray[1] : $inArray[0];
                $output .= $segment;
                $input = substr($input, strlen($segment));
            }
        }
        return $output;
    }

    /**
     * @return string
     */
    public function getUri() {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getScheme() {
        return $this->scheme;
    }

    /**
     * @return string
     */
    public function getAuthority() {
        return $this->authority;
    }

    /**
     * @return string
     */
    public function getUserInfo() {
        return $this->userInfo;
    }

    /**
     * @return string
     */
    public function getHost() {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPort() {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * @return string
     */
    public function getFragment() {
        return $this->fragment;
    }

    /**
     * Use for tests only!
     *
     * @param mixed $field
     * @return mixed
     */
    public function __get($field) {
        return $this->$field;
    }
}
