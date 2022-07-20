<?php

namespace DevAnime\SatoruGojo\FAQ;

use DevAnime\Models\OptionsBase;
use DevAnime\Models\PostCollection;

/**
 * Class FAQSections
 * @package DevAnime\SatoruGojo\FAQ
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 *
 * @method static sections()
 */
class FAQSections extends OptionsBase
{
    protected $default_values = [
        'sections' => []
    ];

    /**
     * Format: $ret[title] => PostCollection
     *
     * @return array
     */
    protected function getSections()
    {
        $ret = [];
        foreach($this->get('sections') as $section) {
            $ret[$section['section_title']] = new PostCollection(array_map([FAQ::class, 'create'], $section['faqs']));
        }
        return $ret;
    }
}
