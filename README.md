# IIIF Manifest Reader for PHP

Objective of this project is to load IIIF Presentation API 2 manifests into accessible PHP objects.

Requires at least PHP 5.6 at the moment.

## TODOs:

* implement ;)
* check if instant loading of large manifests becomes a problem when there are hundreds or thousands of canvases or annotations per manifest
* handle resources that are only referenced and not contained (e.g. manifests in a collection)
* easy Thumbnail-Access
* define minimal PHP version

## Notes
* The weird psr4 autoload configuration in composer.json (`"": "src"`) is due to a (probable) bug in Eclipse PDT's "New Class / Trait / Interface" wizard - namespace suggestions are flawed for composer projects.