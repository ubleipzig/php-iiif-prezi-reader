<?php
namespace iiif\presentation\v2\model;

use PHPUnit\Framework\TestCase;

abstract class AbstractIiifTest extends TestCase
{
    public static function getJson($filename)
    {
        return file_get_contents(__DIR__.'/../../../../resources/'.$filename);
    }
}

