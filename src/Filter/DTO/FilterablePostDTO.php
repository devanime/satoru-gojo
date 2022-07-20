<?php


namespace DevAnime\SatoruGojo\Filter\DTO;


use DevAnime\Models\DTO;
use DevAnime\Models\PostBase;

class FilterablePostDTO extends DTO
{
    protected $id;
    protected $matches;

    public function __construct(PostBase $Post, array $matches = [])
    {
        $this->id = $Post->ID;
        $this->matches = $matches;
    }
}