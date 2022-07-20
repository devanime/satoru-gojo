<?php

namespace DevAnime\SatoruGojo\Video;

/**
 * Interface VideoProvider
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
abstract class VideoProvider
{
    const SERVICE = '';
    protected $url_parser;
    protected $warnings = [];
    public $videoID;
    public $videoMeta;

    /**
     * VideoProvider constructor.
     *
     * @param URLParser $url_parser
     */
    public function __construct(URLParser $url_parser)
    {
        if ($url_parser->provider_name !== static::SERVICE) {
            throw new \InvalidArgumentException('Incorrect video provider: ' . $url_parser->provider_name);
        }
        $this->url_parser = $url_parser;
        $this->videoID = $url_parser->id;
        $this->setVideoMeta();
    }

    public function getWarnings()
    {
        return $this->warnings;
    }

    protected function addWarning($message)
    {
        $this->warnings[] = $message;
    }

    abstract protected function setVideoMeta();
}
