<?php
/**
 * Class VideoDTO
 * @package DevAnime\SatoruGojo\Video
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */

namespace DevAnime\SatoruGojo\Video;

use DevAnime\Models\DTO;

class VideoDTO extends DTO
{
    public $id;
    public $service;
    public $height;
    public $width;
    public $title;
    public $description;
    public $img;
    public $url;


    public static function createFromVideoPost(VideoPost $videoPost)
    {
        $dto = new static();
        $dto->id = $videoPost->video_id;
        $dto->service = $videoPost->service;
        $dto->height = $videoPost->height;
        $dto->width = $videoPost->width;
        $dto->title = $videoPost->post_title;
        $dto->description = $videoPost->content();
        $dto->img = [
            'src'    => $videoPost->featuredImage()->url,
            'height' => $videoPost->featuredImage()->height,
            'width'  => $videoPost->featuredImage()->width
        ];
        $dto->url = $videoPost->video;

        return $dto;
    }
}
