<?php
namespace iiif\presentation\v1\model\resources;

use iiif\presentation\common\model\resources\ContentResourceInterface;
use iiif\services\AbstractImageService;
use iiif\tools\IiifHelper;
use iiif\tools\Options;

class ContentResource1 extends AbstractIiifResource1 implements ContentResourceInterface {
    
    /**
     * @var int
     */
    protected $width;
    
    /**
     * @var int
     */
    protected $height;
    
    /**
     * @var string
     */
    protected $format;

    /**
     * @var string;
     */
    protected $chars;
    /**
     * @return number
     */
    public function getWidth() {
        return $this->width;
    }

    /**
     * @return number
     */
    public function getHeight() {
        return $this->height;
    }

    /**
     * @return string
     */
    public function getFormat() {
        return $this->format;
    }

    /**
     * @return string;
     */
    public function getChars() {
        return $this->chars;
    }
    
    /**
     * {@inheritDoc}
     * @see \iiif\presentation\v1\model\resources\AbstractIiifResource1::getThumbnailUrl()
     */
    public function getThumbnailUrl() {
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

