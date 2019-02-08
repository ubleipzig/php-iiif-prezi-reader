# IIIF Manifest Reader for PHP

Read IIIF Presentation API resources into PHP objects that offer some convenient data extraction methods.

Requires at least PHP 5.6 at the moment.

## Where to start

* Any IIIF manifest (or any other IIIF Presentation API / Metadata API resource json document) as text content, URL or path can be loaded into a PHP object representing the document with `\iiif\tools\IiifHelper::loadIiifResource($resource)`. The object will contain all "child" resources in a property with the name of their properties in the IIIF Metadata API 1 (namespace `iiif\presentation\v1\model\resources`), IIIF Presentation API 2 (`iiif\presentation\v2\model\resources`) or IIIF Presentation API 3 (`iiif\presentation\v3\model\resources`). You can also use most of these resources via interfaces that are defined in `iiif\presentation\common\model\resources`. These interfaces attempt to offer a (somewhat reduced) version independent funtionallity.  
* If you want to use any framework's functionality for retrieving remote documents instead of PHP's `file_get_contents()`, implement `\iiif\tools\UrlReaderInterface` and use `\iiif\tools\IiifHelper::setUrlReader(UrlReaderInterface $urlReader)` before loading any documents.
* Use `\iiif\tools\IiifHelper::setMaxThumbnailWidth($maxThumbnailWidth)` and `\iiif\tools\IiifHelper::setMaxThumbnailHeight($maxThumbnailHeight)` to set the maximum dimensions for resource thumbnails that offer an IIIF image service for generating thumbnails.

## TODOs:

* Support annotation grouping (via Layer for Metadata API 1, Presentation API 2; via AnnotationCollection via Presentation API 3)
* Finish Metadata API 1.0 and Presentation API 3.0 implementation
* Improve annotation support regarding selectors / fragments as annotation targets as well as regarding resource types
* Check performance for large manifests when there are hundreds or thousands of canvases or annotations per manifest
* Handle resources that are only referenced and not contained (e.g. manifests in a collection)
* Improve test coverage

## Notes
* The weird psr4 autoload configuration in composer.json (`"": "src"`) is a workaround for a [bug in Eclipse PDT](https://bugs.eclipse.org/bugs/show_bug.cgi?id=514120)'s "New Class / Trait / Interface" wizard - namespace suggestions are flawed for composer projects.

