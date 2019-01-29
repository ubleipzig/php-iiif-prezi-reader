<?php
namespace iiif\presentation\v2\model\resources;

use iiif\services\AbstractImageService;
use iiif\presentation\v2\model\properties\WidthAndHeightTrait;
use iiif\presentation\common\model\resources\ContentResourceInterface;
use iiif\tools\Options;

class ContentResource extends AbstractIiifResource2 implements ContentResourceInterface {
    use WidthAndHeightTrait;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var string
     */
    protected $chars;

    /**
     *
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     *
     * @return string
     */
    public function getChars() {
        return $this->chars;
    }

    public function getImageUrl() {
        $service = $this->service;
        if ($service instanceof AbstractImageService) {
            return $service->getImageUrl();
        }
    }
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v2\model\resources\AbstractIiifResource2::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
        $result = parent::getThumbnailUrl();
        if ($result != null) {
            return $result;
        }
        $services = is_array($this->service) ? $this->service : [$this->service];
        foreach ($services as $service) {
            if ($service instanceof AbstractImageService) {
                $size = $this->width <= $this->height ? ",".Options::getMaxThumbnailHeight() : (Options::getMaxThumbnailWidth().",");
                return $service->getImageUrl(null, $size);
            }
        }
        return null;
    }


    
}

