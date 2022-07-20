<?php

namespace DevAnime\SatoruGojo\Filter\Group;

use DevAnime\Models\PostBase;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;

/**
 * Class FilterGroupPost
 * @package DevAnime\SatoruGojo\Filter\Group
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 *
 * @property FilterFieldPost[] $fields
 */
class FilterGroupPost extends PostBase
{
    const POST_TYPE = 'filter-group';

    protected function get_fields()
    {
        $fields = [];
        $rows = array_filter((array) $this->field('fields'));
        foreach ($rows as $row) {
            $Field = FilterFieldPost::create($row['field']);
            $fields[$row['label']] = $Field;
        }
        return $fields;
    }
}
