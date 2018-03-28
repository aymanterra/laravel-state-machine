<?php

namespace troojaan\SM\Test;

class Article
{
    public $state;

    public function __construct($state = 'new')
    {
        $this->state = $state;
    }
}
