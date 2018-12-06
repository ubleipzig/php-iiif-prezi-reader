# IIIF Manifest Reader for PHP

Objective of this project is to load IIIF Presentation API manifests into accessible PHP objects.

Requires at least PHP 5.6 at the moment.

## TODOs:

* implement ;)
* check if instant loading of large manifests becomes a problem when there are hundreds or thousands of canvases or annotations per manifest
* handle resources that are only referenced and not contained (e.g. manifests in a collection)
* easy Thumbnail-Access
* define minimal PHP version
* Test coverage

## Notes
* The weird psr4 autoload configuration in composer.json (`"": "src"`) is due to a [bug in Eclipse PDT](https://bugs.eclipse.org/bugs/show_bug.cgi?id=514120)'s "New Class / Trait / Interface" wizard - namespace suggestions are flawed for composer projects.

