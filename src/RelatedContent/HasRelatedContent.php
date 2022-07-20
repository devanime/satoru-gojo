<?php

namespace DevAnime\SatoruGojo\RelatedContent;

/**
 * Interface HasRelatedContent
 * @package DevAnime\Produers\RelatedContent
 * @author DevAnime <devanimecards@gmail.com>
 * @version 1.0
 */
interface HasRelatedContent
{
    /**
     * @return int
     */
    function getPostId(): int;

    /**
     * @param string $context
     * @return array
     */
    function getAssignedIds(string $context = ''): array;

    /**
     * @return array
     */
    function getRelatedTermIds(): array;

    /**
     * @return string
     */
    function getRelatedTaxonomyName(): string;
}
