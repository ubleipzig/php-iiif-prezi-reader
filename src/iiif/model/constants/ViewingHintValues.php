<?php
namespace iiif\model\constants;

class ViewingHintValues
{
    CONST INDIVIDUALS = "individuals";
    CONST PAGED = "paged";
    CONST CONTINUOUS = "continuous";
    CONST MULTI_PART = "multi-part";
    CONST NON_PAGED = "non-paged";
    CONST TOP = "top";
    CONST FACING_PAGES = "facing-pages";
    
    CONST ALLOWED_VALUES = [
        ViewingHintValues::INDIVIDUALS,
        ViewingHintValues::PAGED,
        ViewingHintValues::CONTINUOUS,
        ViewingHintValues::MULTI_PART,
        ViewingHintValues::NON_PAGED,
        ViewingHintValues::TOP,
        ViewingHintValues::FACING_PAGES
    ];
}

