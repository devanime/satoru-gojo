<?php

namespace DevAnime\SatoruGojo\Tour;

use DevAnime\Repositories\PostRepository;
use DevAnime\Support\DateTime;

/**
 * Class TourCityRepository
 * @package DevAnime\SatoruGojo\Tour
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class TourCityRepository extends PostRepository
{
    protected $model_class = TourCity::class;

    public function findFuture(): array
    {
        $now = new DateTime('now');
        return $this->find([
            'orderby' => 'meta_value',
            'meta_key' => 'start_date',
            'order' => 'ASC',
            'meta_query' => [
                'relation' => 'OR',
                [
                    [
                        'key' => 'start_date',
                        'value' => '',
                        'compare' => '='
                    ],
                    [
                        'key' => 'end_date',
                        'value' => '',
                        'compare' => '='
                    ]
                ],
                [
                    'key' => 'end_date',
                    'compare' => '>=',
                    'value' => $now->format('Ymd'),
                    'type' => 'DATE'
                ]
            ]
        ]);
    }
}
