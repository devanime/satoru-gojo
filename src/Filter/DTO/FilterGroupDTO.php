<?php


namespace DevAnime\SatoruGojo\Filter\DTO;


use DevAnime\Models\DTO;
use DevAnime\Models\DTOCollection;
use DevAnime\SatoruGojo\Filter\FilterFieldPost;

class FilterGroupDTO extends DTOCollection
{
    /**
     * @param FilterFieldPost $item
     * @return DTO
     */
    protected function createDTO($item): DTO
    {
        return null;
    }
}