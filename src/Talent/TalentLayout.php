<?php

namespace DevAnime\SatoruGojo\Talent;

/**
 * Class TalentLayout
 * @package DevAnime\SatoruGojo\Talent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class TalentLayout
{
    protected $grids;

    public function __construct()
    {
        $grids = $this->getField('grids');
        $this->grids = array_filter(array_map(function($grid) {
            return !empty($grid['rows']) ?
                new TalentGrid(new TalentType($grid['talent_type']), $grid['rows']) : false;
        }, (array) $grids));
    }

    public function getTabs(): array
    {
        return array_map(function(TalentGrid $grid) {
            return $grid->getNavLink();
        }, $this->grids);
    }

    /**
     * @return TalentGrid[]
     */
    public function getGrids(): array
    {
        return $this->grids;
    }

    // Helpers

    public function getField($name)
    {
        return get_field($name, 'option');
    }
}
