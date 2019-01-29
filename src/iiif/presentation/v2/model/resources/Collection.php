<?php
namespace iiif\presentation\v2\model\resources;

use iiif\presentation\v2\model\properties\NavDateTrait;

class Collection extends AbstractIiifResource2 {
    use NavDateTrait;

    const TYPE = "sc:Collection";

    protected $navDate;

    /**
     *
     * @var Collection[]
     */
    protected $collections = array();

    /**
     *
     * @var Manifest[]
     */
    protected $manifests = array();

    protected $members = array();

}

