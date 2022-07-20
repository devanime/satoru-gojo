<?php

namespace DevAnime\SatoruGojo\FAQ;

use DevAnime\Models\PostBase;

/**
 * Class FAQ
 * @package DevAnime\SatoruGojo\FAQ
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class FAQ extends PostBase
{
    const POST_TYPE = 'faq';
    const TAXONOMY = 'faq-category';
}
