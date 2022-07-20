<?php

namespace DevAnime\SatoruGojo\RelatedContent;

use DevAnime\Repositories\PostRepository;

/**
 * class RelatedContentRepository
 * @package DevAnime\SatoruGojo\RelatedContent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
abstract class RelatedContentRepository extends PostRepository
{
    protected $related_model_class = null;

    public function findAssigned(HasRelatedContent $Source, int $limit = 3)
    {
        $context = $this->getAssignedField();
        return $this->findWithIds($Source->getAssignedIds($context), $limit);
    }

    public function findRelated(HasRelatedContent $Source, int $limit = 3)
    {
        return $this->find([
            'posts_per_page' => $limit,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => $this->getRelatedField(),
                    'value' => $Source->getPostId()
                ],
                [
                    'key' => $this->getRelatedField(),
                    'compare' => 'EXISTS'
                ]
            ]
        ]);
    }

    public function findRelatedWithTerms(HasRelatedContent $Source, int $limit = 3)
    {
        $term_ids = $Source->getRelatedTermIds();
        return !empty($term_ids) ? $this->findWithTermIds(
            $term_ids,
            $Source->getRelatedTaxonomyName(),
            $limit
        ) : [];
    }

    protected function getAssignedField()
    {
        return sprintf('assigned_%s_ids', $this->model_class::POST_TYPE);
    }

    protected function getRelatedField()
    {
        return sprintf('related_%s_id', $this->related_model_class::POST_TYPE);
    }
}
