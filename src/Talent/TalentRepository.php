<?php

namespace DevAnime\SatoruGojo\Talent;
use DevAnime\Repositories\PostRepository;

/**
 * Class TalentRepository
 * @package DevAnime\SatoruGojo\Talent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class TalentRepository extends PostRepository
{
    protected $model_class = TalentPost::class;

    public function findTalentWithType($type)
    {
        return $this->find([
            'tax_query' => [
                [
                    'taxonomy' => TalentPost::TAXONOMY,
                    'field' => 'slug',
                    'terms' => $type
                ]
            ]
        ]);
    }
}
