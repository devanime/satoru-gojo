<?php

namespace DevAnime\SatoruGojo\Filter\Group;

use DevAnime\Repositories\PostRepository;

/**
 * Class FilterGroupRepository
 * @package DevAnime\SatoruGojo\Filter\Group
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class FilterGroupRepository extends PostRepository
{
    protected $model_class = FilterGroupPost::class;
}
