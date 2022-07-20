<?php

namespace DevAnime\SatoruGojo\Talent;
use DevAnime\Models\PostBase;

/**
 * Class TalentPost
 * @package DevAnime\SatoruGojo\Talent
 * @author  DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property string $role
 * @property string $bio
 * @property \WP_Image $headshot
 * @property \WP_Term $talent_type
 * @property string $type_name
 * @property string $type_slug
 */
class TalentPost extends PostBase
{
    const POST_TYPE = 'talent';
    const TAXONOMY = 'talent-type';

    protected function get_talent_type()
    {
        $talent_type = $this->terms(static::TAXONOMY);
        return reset($talent_type);
    }

    protected function get_type_name()
    {
        $talent_type = $this->talent_type;
        return $talent_type ? $talent_type->name : '';
    }

    protected function get_type_slug()
    {
        $talent_type = $this->talent_type;
        return $talent_type ? $talent_type->slug : '';
    }

    protected function get_bio()
    {
        return $this->content();
    }

    protected function get_headshot()
    {
        return $this->featuredImage();
    }

}
