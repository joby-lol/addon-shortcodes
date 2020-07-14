<?php
namespace Leafcutter\Addons\Leafcutter\Shortcodes\Filters;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;
use Leafcutter\Leafcutter;
use Leafcutter\URL;

class ImageFilter extends AbstractFilter
{
    /**
     * Regex pattern.
     */
    const IMAGE_PATTERN = '/^((?:https?:\/)?(?:\.){0,2}\/)((?:.*?)\.(jpg|jpeg|png|gif|bmp|svg))(\?[^#]+)?(#[\-\w]+)?$/is';
    const WIDTH_HEIGHT = '/^([0-9%]{1,4}+)x([0-9%]{1,4}+)$/';
    const DIMENSION = '/^[0-9%]{1,4}+$/';

    /**
     * Supported tags.
     *
     * @type array
     */
    protected $_tags = array(
        'img' => array(
            'template' => 'leafcutter-image',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_NONE,
            'contentPattern' => self::IMAGE_PATTERN,
            'autoClose' => true,
            'attributes' => array(
                'default' => self::WIDTH_HEIGHT,
                'width' => self::DIMENSION,
                'height' => self::DIMENSION,
                'alt' => self::WILDCARD,
                'link' => self::WILDCARD,
                'class' => self::WILDCARD,
                'preset' => self::WILDCARD,
            ),
        ),
        'image' => array(
            'aliasFor' => 'img',
        ),
    );

    public function parse(array $tag, $content)
    {
        // split out width/height
        if (!empty(@$tag['attributes']['default'])) {
            list($width, $height) = explode('x', $tag['attributes']['default']);

            $tag['attributes']['width'] = $width;
            $tag['attributes']['height'] = $height;
        }
        // try to get image from leafcutter
        $leafcutter = Leafcutter::get();
        if ($image = $leafcutter->images()->get(new URL($content))) {
            if (@$tag['attributes']['preset']) {
                $asset = $image->preset($tag['attributes']['preset']);
            }
            if (!$asset && @$tag['attributes']['width'] && @$tag['attributes']['height']) {
                $asset = $image->crop($tag['attributes']['width'], $tag['attributes']['height']);
            }
            if (!$asset) {
                $asset = $image->default();
            }
            $content = $asset->publicUrl()->__toString();
            // if link is set, find its asset URL
            if (!empty(@$tag['attributes']['link'])) {
                if ($asset = $image->preset($tag['attributes']['link'])) {
                    $tag['attributes']['link'] = $asset->publicUrl()->__toString();
                }
            }
        }
        // If more than 1 http:// is found in the string, possible XSS attack
        if ((mb_substr_count($content, 'http://') + mb_substr_count($content, 'https://')) > 1) {
            return null;
        }
        // set src
        $tag['attributes']['src'] = $content;
        // empty alt
        if (empty(@$tag['attributes']['alt'])) {
            $tag['attributes']['alt'] = '';
        }
        // parse and return
        return parent::parse($tag, $content);
    }
}
