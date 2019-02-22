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
    const IRI_REGEX = '_^((?P<scheme>[^:/?#]+):)?((?P<doubleSlash>//)(?P<authority>([^/?#]*)))?(?P<path>[^?#]*)(\?(?P<query>[^#]*))?(#(?P<fragment>.*))?_';

    const COMPACT_IRI_REGEX = "_^(?P<prefix>[^:/?#]+):(?P<term>[^:/?#]+)_";
    
    /**
     * @var string
     */
    protected $iri;

    /**
     * @var string
     */
    protected $scheme;
    
    /**
     * @var string
     */
    protected $doubleSlash;

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

    /**
     * @param string|IRI $iri A string representation to read into an IRI object or an IRI object to copy  
     */
    public function __construct($iri = null) {
        if (is_string($iri) && self::isIri($iri)) {
            $this->iri = $iri;
            $found = preg_match(self::IRI_REGEX, $iri, $matches);
            foreach ($matches as $key => $value) {
                if (is_numeric($key)) {
                    continue;
                }
                $this->$key = $value;
            }
            if (!empty($this->authority)) {
                $hostPort = $this->authority;
                if (($atPos = strpos($hostPort, '@')) !== false) {
                    $this->userInfo = substr($hostPort, 0, $atPos);
                    $hostPort = substr($hostPort,$atPos + 1);
                }
                if (($colonPos = strrpos($hostPort, ':')) !== false && preg_match('_^\d{1,5}$_', $port = substr($hostPort, $colonPos+1))) {
                    $this->host = substr($hostPort, 0, $colonPos);
                    $this->port = $port;
                } else {
                    $this->host = $hostPort;
                }
            }
        } elseif ($iri instanceof IRI) {
            $this->iri = $iri->iri;
            $this->scheme = $iri->scheme;
            $this->doubleSlash = $iri->doubleSlash;
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
     * Checks if a given string is a compact IRI. It therefore has to have a prefix and a term definition for the prefix in the current JSON-LD context. 
     * @param string $iri
     * @param JsonLdContext $context
     * @return boolean
     */
    public static function isCompactIri($iri, JsonLdContext $context) {
        if (!preg_match(self::COMPACT_IRI_REGEX, $iri)) {
            return false;
        }
        preg_match(self::COMPACT_IRI_REGEX, $iri, $matches);
        $prefix = $matches["prefix"];
        return !empty($prefix) && $context->getTermDefinition($prefix) != null && !empty($context->getTermDefinition($prefix)->getIriMapping());
    }

    /**
     * Checks if a string has the form of an IRI by matching the regex in https://tools.ietf.org/html/rfc3986#appendix-B
     * @param string $iri
     * @return boolean
     * @link https://tools.ietf.org/html/rfc3986#appendix-B
     */
    public static function isIri($iri) {
        if ($iri == null)
        {
            return false;
        }
        return preg_match(IRI::IRI_REGEX, $iri) === 1;
    }

    /**
     * Checks if an IRI is an absolute IRI for JSON-LD purposes.
     * From https://www.w3.org/TR/json-ld11-api/#dfn-absolute-iri :
     * "An absolute IRI is defined in [RFC3987] containing a scheme along with
     * a path and optional query and fragment segments."
     *  
     * @param string $iri
     * @return boolean true
     */
    public static function isAbsoluteIri($iri) {
        $iriObject = new IRI($iri);
        return self::isIri($iri) && ! empty($iriObject->scheme) && ! empty($iriObject->path) && ! (strpos(trim($iri), "{") === 0 && strrpos(trim($iri), "}") === strlen(trim($iri))-1);
    }

    /**
     * Checks if an IRI is a relative IRI. For that purpose we check if it
     * matches the IRI regex and misses a scheme.
     * @param string $iri
     * @return boolean true if $iri has the form of a relative IRI, otherwise false
     */
    public static function isRelativeIri($iri) {
        return self::isIri($iri) && empty((new IRI($iri))->scheme);
    }

    /**
     * Resolve an absolute IRI by a given base IRI and the relative IRI that needs to be resolved.
     * Implementation of the algorithm described in https://tools.ietf.org/html/rfc3986#section-5.2
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
            $doubleSlash = $rel->getDoubleSlash();
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
            $doubleSlash = $base->getDoubleSlash();
        }
        $fragment = $rel->getFragment();
        $result = (empty($schema) ? "" : ($schema . ":")). (empty($doubleSlash) ? "" : $doubleSlash) . $authority . $path . (empty($query) ? "" : "?" . $query) . (empty($fragment) ? "" : ("#" . $fragment));
        return (empty($schema) ? "" : ($schema . ":")). (empty($doubleSlash) ? "" : $doubleSlash) . $authority . $path . (empty($query) ? "" : "?" . $query) . (empty($fragment) ? "" : ("#" . $fragment));
    }

    /**
     * Remove dot segments ( ".." and "." ) in IRI paths. Implements the algorithm
     * described in https://tools.ietf.org/html/rfc3986#section-5.2.4
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
            } elseif (strpos($input, "/../") === 0 || $input === "/..") {
                if (strpos($input, "/../") === 0) {
                    $input = substr($input, 3);
                } else {
                    $input = "/" . substr($input, 3);
                }
                $outArray = explode("/", $output);
                $segment = empty($outArray[count($outArray) - 1]) && count($outArray) > 1 ? $outArray[count($outArray) - 2] . "/" : $outArray[count($outArray) - 1];
                $outIsSlash = $output === "/";
                $output = substr($output, 0, strlen($output) - strlen($segment));
                if (strrpos($output, "/") == ($length = strlen($output) - 1) && !$outIsSlash) {
                    $output = substr($output, 0, $length);
                }
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
    public function getIri() {
        return $this->iri;
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
    public function getDoubleSlash() {
        return $this->doubleSlash;
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
        $stack = debug_backtrace();
        foreach ($stack as $line) {
            if (strpos($line["function"], "test") === 0 && $line["function"]!="test__GetFails") {
                return $this->$field;
            }
        }
    }
}
