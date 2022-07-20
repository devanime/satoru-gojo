<?php


namespace DevAnime\SatoruGojo\Filter\DTO;


use DevAnime\Models\DTO;
use DevAnime\Models\DTOCollection;
use DevAnime\Models\PostBase;

class FilterablePostsDTO extends DTOCollection
{
    /**
     * @param PostBase $item
     * @return DTO
     */
    protected function createDTO($item): DTO
    {
        return new FilterablePostDTO($item);
    }

}