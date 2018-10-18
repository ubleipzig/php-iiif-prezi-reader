<?php
namespace iiif\presentation\v2\model\constants;

class ViewingDirectionValues {

    const LEFT_TO_RIGHT = "left-to-right";

    const RIGHT_TO_LEFT = "right-to-left";

    const TOP_TO_BOTTOM = "top-to-bottom";

    const BOTTOM_TO_TOP = "bottom-to-top";

    const ALLOWED_VALUES = [
        self::LEFT_TO_RIGHT,
        self::RIGHT_TO_LEFT,
        self::TOP_TO_BOTTOM,
        self::BOTTOM_TO_TOP
    ];
}

