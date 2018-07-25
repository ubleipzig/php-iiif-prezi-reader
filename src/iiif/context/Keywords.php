<?php
namespace iiif\context;


class Keywords
{
    CONST BASE = "@base";
    CONST CONTAINER = "@container";
    CONST CONTEXT = "@context";
    CONST GRAPH = "@graph";
    CONST ID = "@id";
    CONST INDEX = "@index";
    CONST LANGUAGE = "@language";
    CONST LIST = "@list";
    CONST NEST = "@nest";
    CONST NONE = "@none";
    CONST PREFIX = "@prefix";
    CONST REVERSE = "@reverse";
    CONST SET = "@set";
    CONST TYPE = "@type";
    CONST VALUE = "@value";
    CONST VERSION = "@version";
    CONST VOCAB = "@vocab";
    
    CONST KEYWORDS = array(
        self::BASE,
        self::CONTAINER,
        self::CONTEXT,
        self::GRAPH,
        self::ID,
        self::INDEX,
        self::LANGUAGE,
        self::LIST,
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

    public static function isKeyword($value) {
        return array_search($value, self::KEYWORDS)!==false;
    }

}

