<?php

namespace DevAnime\SatoruGojo\Talent;
use DevAnime\Models\TermBase;

/**
 * Class TalentType
 * @package DevAnime\SatoruGojo\Talent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class TalentType extends TermBase
{
    const TAXONOMY = 'talent-type';

    public function getName()
    {
        return $this->term()->name;
    }

    public function getSlug()
    {
        return $this->term()->slug;
    }

    public function getHashSlug()
    {
        return "#{$this->getSlug()}";
    }
}
