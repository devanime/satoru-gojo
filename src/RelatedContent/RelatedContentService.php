<?php

namespace DevAnime\SatoruGojo\RelatedContent;

/**
 * Class RelatedContentService
 * @package DevAnime\SatoruGojo\RelatedContent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
class RelatedContentService
{
    const REPOSITORY_METHODS = ['findAssigned', 'findRelated', 'findRelatedWithTerms'];

    protected $Source;
    protected $RelatedContentRepository = null;
    protected $RelatedContentCollection = null;

    public function __construct(HasRelatedContent $Source, RelatedContentRepository $RelatedContentRepository, int $count = 3)
    {
        $this->Source = $Source;
        $this->RelatedContentRepository = $RelatedContentRepository;
        $this->RelatedContentCollection = new RelatedContentCollection([], $count);
    }

    /**
     * @return RelatedContentCollection|null
     */
    public function getRelatedContent()
    {
        $methods = static::REPOSITORY_METHODS;
        array_walk($methods, [$this, 'addToCollection']);
        return $this->RelatedContentCollection;
    }

    /**
     * @param $method
     */
    protected function addToCollection(string $method)
    {
        if ($this->RelatedContentCollection->isFull()) {
            return;
        }
        $related_content = call_user_func(
            [$this->RelatedContentRepository, $method],
            $this->Source, $this->RelatedContentCollection->getMaxCount()
        );
        while (!empty($related_content)) {
            $this->RelatedContentCollection = $this->RelatedContentCollection->add(array_shift($related_content));
        }
    }

}
