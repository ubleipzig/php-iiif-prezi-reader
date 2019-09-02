<?php
namespace Ubl\Iiif;

use Ubl\Iiif\Tools\UrlReaderInterface;

/**
 * Load certain URLs from file system instead from remote; count url retrieval 
 *
 *
 * @author Lutz Helm <helm@ub.uni-leipzig.de>
 */
class UrlReaderForTests implements UrlReaderInterface {

    /**
     * @var array
     */
    protected $requestedUrls = [];

    public function getContent($url) {
        if (array_key_exists($url, $this->requestedUrls)) {
            $this->requestedUrls[$url]++;
        } else {
            $this->requestedUrls[$url] = 1;
        }
        
        if (strpos($url, "http://example.org/iiif/book1/list/") === 0) {
            $url = __DIR__ . str_replace("http://example.org/iiif/book1/list/", "/../resources/v2/lazy-loading-", $url) . ".json";
        }
        return file_get_contents($url);
    }

    public function resetRequestedUrls() {
        $this->requestedUrls = [];
    }

    public function getRequestedUrls() {
        return $this->requestedUrls; 
    }

}

