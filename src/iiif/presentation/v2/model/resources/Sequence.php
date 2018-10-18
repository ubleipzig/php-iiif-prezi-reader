<?php
namespace iiif\presentation\v2\model\resources;

include_once 'AbstractIiifResource.php';

use iiif\presentation\v2\model\properties\ViewingDirectionTrait;
use iiif\presentation\v2\model\vocabulary\Names;
use iiif\presentation\v2\model\properties\StartCanvasTrait;

class Sequence extends AbstractIiifResource {
    use ViewingDirectionTrait;
    use StartCanvasTrait;

    const TYPE = "sc:Sequence";

    /**
     *
     * @var Canvas[]
     */
    protected $canvases = array();

    protected $viewingDirection;

    /**
     *
     * {@inheritdoc}
     * @see \iiif\presentation\common\model\AbstractIiifEntity::getStringResources()
     */
    protected function getStringResources() {
        return [
            "startCanvas" => Canvas::class
        ];
    }

    /**
     *
     * @return multitype:\iiif\model\resources\Canvas
     */
    public function getCanvases() {
        return $this->canvases;
    }

    public function getStartCanvasOrFirstCanvas() {
        if (isset($this->startCanvas)) {
            return $this->startCanvas;
        } elseif (isset($this->canvases) && sizeof($this->canvases) > 0) {
            return $this->canvases[0];
        }
    }
}

