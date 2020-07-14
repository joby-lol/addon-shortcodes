<?php
namespace Leafcutter\Addons\Leafcutter\Shortcodes;

use Decoda\Decoda;
use Decoda\Filter\DefaultFilter;
use Leafcutter\Pages\PageContentEvent;
use Leafcutter\Pages\PageEvent;

class Addon extends \Leafcutter\Addons\AbstractAddon
{
    /**
     * Specify default config here. If it must include dynamic content, or
     * for some other reason can't be a constant, delete this constant and
     * override the method `getDefaultConfig()` instead.
     */
    const DEFAULT_CONFIG = [];
    protected $decoda;

    public function onPageFileContents(string $content): string
    {
        return $this->parse($content);
    }

    protected function decoda($string = '') : Decoda
    {
        if ($this->decoda === null) {
            $this->decoda = new Decoda('', [
                'escapeHtml' => false,
                'strictMode' => false,
                'lineBreaks' => false,
            ]);
            //set up engine
            $this->decoda->getEngine()->addPath(__DIR__ . '/../templates/');
            //set up filters
            $this->decoda->addFilter(new DefaultFilter());
            $this->decoda->addFilter(new Filters\ImageFilter());
            $this->decoda->addFilter(new Filters\LinkFilter());
            $this->decoda->addFilter(new Filters\GalleryFilter());
        }
        $this->decoda->reset($string);
        return $this->decoda;
    }

    public function parse($string): string
    {
        return $this->decoda($string)->parse();
    }

    /**
     * Method is executed as the first step when this Addon is activated.
     *
     * @return void
     */
    public function activate(): void
    {
    }

    /**
     * Used after loading to give Leafcutter an array of event subscribers.
     * An easy way of rapidly developing simple Addons is to simply return [$this]
     * and put your event listener methods in this same single class.
     *
     * @return array
     */
    public function getEventSubscribers(): array
    {
        return [$this];
    }

    /**
     * Specify the names of the features this Addon provides. Some names may require
     * you to implement certain interfaces. Addon will also be available from
     * AddonProvider::get() by any names given here.
     *
     * @return array
     */
    public static function provides(): array
    {
        return ['shortcodes'];
    }

    /**
     * Specify an array of the names of features this Addon requires. Leafcutter
     * will attempt to automatically load the necessary Addons to provide these
     * features when this Addon is loaded.
     *
     * @return array
     */
    public static function requires(): array
    {
        return [];
    }

    /**
     * Return the canonical name of this plugin. Generally this should be the
     * same as the composer package name, so this example pulls it from your
     * composer.json automatically.
     *
     * @return string
     */
    public static function name(): string
    {
        if ($data = json_decode(file_get_contents(__DIR__ . '/../composer.json'), true)) {
            return $data['name'];
        }
        return 'unknown/unknownaddon';
    }
}
