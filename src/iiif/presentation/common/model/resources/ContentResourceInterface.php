<?php
namespace iiif\presentation\common\model\resources;

interface ContentResourceInterface extends IiifResourceInterface {
    
    /**
     * @return int
     */
    public function getWidth();
    
    /**
     * @return int
     */
    public function getHeight();
    
    /**
     * @return string
     */
    public function getFormat();
    
    /**
     * @return string
     */
    public function getChars();

}

