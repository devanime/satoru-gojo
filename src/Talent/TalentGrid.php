<?php

namespace DevAnime\SatoruGojo\Talent;
use DevAnime\Estarossa\SubNav\NavLink;

/**
 * Class TalentGrid
 * @package DevAnime\SatoruGojo\Talent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property TalentType $talent_type
 * @property array $rows
 */
class TalentGrid
{
    protected $talent_type;
    protected $rows;
    protected $nav_link;

    public function __construct(TalentType $talent_type, array $rows = [])
    {
        $this->talent_type = $talent_type;
        $this->rows = $rows;
        $this->nav_link = NavLink::create(
            $talent_type->getHashSlug(),
            $talent_type->getName()
        );
    }

    public function getTalentTypeSlug()
    {
        return $this->talent_type->getSlug();
    }

    public function getNavLink() : NavLink
    {
        return $this->nav_link;
    }

    public function hasRows()
    {
        return !empty($this->rows);
    }

    /**
     * @return TalentRow[]
     */
    public function getRows()
    {
        return $this->hasRows() ? array_map(function($row) {
            return new TalentRow($row);
        }, $this->rows) : [];
    }
}
