<?php
/**
 * Class URLParser
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */

namespace DevAnime\SatoruGojo\Video;

class URLParser
{
    public $provider_name;
    public $id;
    public $type = 'video';
    public $url;
    public $html;

    public function __construct($url)
    {
        if (! $this->setVimeoProps($this->parseVimeoID($url))) {
            $this->setYoutubeProps($this->parseYoutubeID($url));
        }
    }

    protected function parseYoutubeID($url)
    {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $url, $match);

        return $match[1];
    }

    protected function parseVimeoID($url)
    {
        if (strpos($url, 'vimeo') === false) {
            return false;
        }
        $path = parse_url($url, PHP_URL_PATH);
        $path = array_slice(array_filter(explode('/', $path)), 0, 2);

        return implode('/', $path);
    }

    protected function setYoutubeProps($id): bool
    {
        if (! $id) {
            return false;
        }
        $this->id = $id;
        $this->provider_name = 'youtube';
        $this->url = 'https://www.youtube.com/watch?v=' . $id;
        $this->html = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $id . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        return true;
    }

    protected function setVimeoProps($id): bool
    {
        if (! $id) {
            return false;
        }
        $this->id = $id;
        $this->provider_name = 'vimeo';
        $this->url = 'https://vimeo.com/' . $id;
        $this->html = '<iframe src="https://player.vimeo.com/video/' . $id . '" width="426" height="240" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
        return true;
    }
}
