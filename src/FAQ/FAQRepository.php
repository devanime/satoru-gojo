<?php

namespace DevAnime\SatoruGojo\FAQ;

use DevAnime\Repositories\PostRepository;

/**
 * Class FAQRepository
 * @package DevAnime\SatoruGojo\FAQ
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class FAQRepository extends PostRepository
{
    protected $model_class = FAQ::class;
}
