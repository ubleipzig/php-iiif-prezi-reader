<?php
namespace iiif\context;

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

    public static function isKeyword($value) {
        return array_search($value, self::KEYWORDS) !== false;
    }
}

