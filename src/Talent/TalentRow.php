<?php

namespace DevAnime\SatoruGojo\Talent;

/**
 * Class TalentRow
 * @package DevAnime\SatoruGojo\Talent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 * @property array $columns
 */
class TalentRow
{
    protected $columns;

    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    public function hasColumns()
    {
        return !empty($this->columns);
    }

    public function getOptions()
    {
        return array_filter((array) $this->columns['layout_options']);
    }

    /**
     * @return TalentPost[]
     */
    public function getColumns()
    {
        return $this->hasColumns() ? array_map(function($talent) {
            return new TalentPost($talent);
        }, reset($this->columns)) : [];
    }
}
