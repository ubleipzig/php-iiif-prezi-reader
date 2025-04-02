# IIIF Manifest Reader for PHP

Read [IIIF](https://iiif.io/) Presentation API resources into PHP objects that offer some convenient data extraction methods.

Requires at least PHP 5.6.

IIIF Presentation API support is primarily implemented for version [2.1](https://iiif.io/api/presentation/2.1/), with some rudimentary support for [Presentation API 3.0](https://iiif.io/api/presentation/3.0/) and [Metadata API 1.0](https://iiif.io/api/metadata/1.0/).

## Where to start

* Any IIIF manifest (or any other IIIF Presentation API / Metadata API resource json document) as text content, URL or path can be loaded into a PHP object representing the document with `\Ubl\Iiif\Tools\IiifHelper::loadIiifResource($resource)`. The object will contain all "child" resources in a property with the name of their properties in the IIIF Metadata API 1 (namespace `Ubl\Iiif\Presentation\V1\Model\Resources`), IIIF Presentation API 2 (`Ubl\Iiif\Presentation\V2\Model\Resources`) or IIIF Presentation API 3 (`Ubl\Iiif\Presentation\V3\Model\Resources`). You can also use most of these resources via interfaces that are defined in `Ubl\Iiif\Presentation\Common\Model\Resources`. These interfaces attempt to offer a (somewhat reduced) version independent functionality.  
* If you want to use any framework's functionality for retrieving remote documents instead of PHP's `file_get_contents()`, implement `\Ubl\Iiif\Tools\UrlReaderInterface` and use `\Ubl\Iiif\Tools\IiifHelper::setUrlReader(UrlReaderInterface $urlReader)` before loading any documents.
* Use `\Ubl\Iiif\Tools\IiifHelper::setMaxThumbnailWidth($maxThumbnailWidth)` and `\Ubl\Iiif\Tools\IiifHelper::setMaxThumbnailHeight($maxThumbnailHeight)` to set the maximum dimensions for resource thumbnails that offer an IIIF image service for generating thumbnails.

## TODOs:

* Support annotation grouping (via Layer for Metadata API 1, Presentation API 2; via AnnotationCollection via Presentation API 3)
* Finish Metadata API 1.0 and Presentation API 3.0 implementation
* Improve annotation support regarding selectors / fragments as annotation targets as well as regarding resource types
* Check performance for large manifests when there are hundreds or thousands of canvases or annotations per manifest
* Handle resources that are only referenced and not contained (e.g. manifests in a collection)
* Improve test coverage
