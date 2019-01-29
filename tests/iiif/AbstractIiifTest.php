<?php
namespace iiif;

use PHPUnit\Framework\TestCase;

abstract class AbstractIiifTest extends TestCase
{
    public static function getFile($filename)
    {
        return file_get_contents(__DIR__.'/../resources/'.$filename);
    }
}

