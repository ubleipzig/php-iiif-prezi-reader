<?php
namespace iiif\context;

/**
 * JSON-LD Keywords
 * 
 * @lnk https://www.w3.org/TR/json-ld11/#syntax-tokens-and-keywords
 * @author lutzhelm
 *
 */
class Keywords {

    const BASE = "@base";

    const CONTAINER = "@container";

    const CONTEXT = "@context";

    const GRAPH = "@graph";

    const ID = "@id";

    const INDEX = "@index";

    const LANGUAGE = "@language";

    const LIST_ = "@list";

    const NEST = "@nest";

    const NONE = "@none";

    const PREFIX = "@prefix";

    const REVERSE = "@reverse";

    const SET = "@set";

    const TYPE = "@type";

    const VALUE = "@value";

    const VERSION = "@version";

    const VOCAB = "@vocab";

    const KEYWORDS = array(
        self::BASE,
        self::CONTAINER,
        self::CONTEXT,
        self::GRAPH,
        self::ID,
        self::INDEX,
        self::LANGUAGE,
        self::LIST_,
        self::NEST,
        self::NONE,
        self::PREFIX,
        self::REVERSE,
        self::SET,
        self::TYPE,
        self::VALUE,
        self::VERSION,
        self::VOCAB
    );

    /**
     * 
     * @param string $term
     * @return boolean true if the given $term is a JSON-LD keyword, otherwise false
     * @link https://www.w3.org/TR/json-ld11/#syntax-tokens-and-keywords
     */
    public static function isKeyword($term) {
        return array_search($term, self::KEYWORDS) !== false;
    }
}

