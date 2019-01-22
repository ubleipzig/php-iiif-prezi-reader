# IIIF Manifest Reader for PHP

Objective of this project is to load IIIF Presentation API manifests into accessible PHP objects.

Requires at least PHP 5.6 at the moment.

## Where to start

* If you have a IIIF manifest (or any other IIIF document) as text content, URL or path, use `\iiif\tools\IiifHelper::loadIiifResource($resource)` to get a PHP object representing the document.
* If you want to use any framework's functionality for retrieving remote documents, implement `\iiif\tools\UrlReaderInterface` and use `\iiif\tools\IiifHelper::setUrlReader(UrlReaderInterface $urlReader)` before loading any documents.

## TODOs:

* Implement Layer support for all API versions
* Finish Metadata API 1.0 and Presentation API 3.0 implementation  
* Check if instant loading of large manifests becomes a performance problem when there are hundreds or thousands of canvases or annotations per manifest
* Handle resources that are only referenced and not contained (e.g. manifests in a collection)
* Improve test coverage

## Notes
* The weird psr4 autoload configuration in composer.json (`"": "src"`) is a workaround for a [bug in Eclipse PDT](https://bugs.eclipse.org/bugs/show_bug.cgi?id=514120)'s "New Class / Trait / Interface" wizard - namespace suggestions are flawed for composer projects.

