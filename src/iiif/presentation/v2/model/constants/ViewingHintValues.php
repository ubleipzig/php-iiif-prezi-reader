<?php
namespace iiif\presentation\v2\model\constants;

class ViewingHintValues {

    const INDIVIDUALS = "individuals";

    const PAGED = "paged";

    const CONTINUOUS = "continuous";

    const MULTI_PART = "multi-part";

    const NON_PAGED = "non-paged";

    const TOP = "top";

    const FACING_PAGES = "facing-pages";

    const ALLOWED_VALUES = [
        ViewingHintValues::INDIVIDUALS,
        ViewingHintValues::PAGED,
        ViewingHintValues::CONTINUOUS,
        ViewingHintValues::MULTI_PART,
        ViewingHintValues::NON_PAGED,
        ViewingHintValues::TOP,
        ViewingHintValues::FACING_PAGES
    ];
}

