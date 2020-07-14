<?php
namespace Leafcutter\Addons\Leafcutter\Shortcodes\Filters;

use Decoda\Decoda;
use Decoda\Filter\AbstractFilter;
use Leafcutter\Leafcutter;

class LinkFilter extends AbstractFilter
{
    protected $_tags = [
        'link' => [
            'htmlTag' => 'a',
            'displayType' => Decoda::TYPE_INLINE,
            'allowedTypes' => Decoda::TYPE_INLINE,
            'autoClose' => false,
            'attributes' => [],
        ]
    ];

    public function link(array $tag, $content)
    {
        // try to get page from Leafcutter
        $leafcutter = Leafcutter::get();
        if ($target = $leafcutter->find($content)) {
            $tag['attributes']['href'] = $target->url()->__toString();
            // change content to target name
            $content = $target->name() ?? 'Untitled';
            // parse and return
            return [$tag, $content];
        } else {
            return '(invalid link)';
        }
    }
}
