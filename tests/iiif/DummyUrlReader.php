<?php
namespace iiif;

use iiif\tools\UrlReaderInterface;

class DummyUrlReader implements UrlReaderInterface {

    public function getContent($url) {
        return '{"@context":"http://iiif.io/api/presentation/2/context.json","@id":"'.$url.'","@type":"sc:Manifest"}';
    }
}

