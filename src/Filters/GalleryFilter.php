<?php
namespace Leafcutter\Addons\Leafcutter\Shortcodes\Filters;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;

class GalleryFilter extends AbstractFilter
{
    protected $_tags = [
        'gallery' => [
            'template' => 'leafcutter-gallery',
            'displayType' => Decoda::TYPE_BLOCK,
            'allowedTypes' => Decoda::TYPE_BLOCK,
            'attributes' => [
                'thumb' => self::WILDCARD,
                'full' => self::WILDCARD,
            ],
        ],
    ];
}
