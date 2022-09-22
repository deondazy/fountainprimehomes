<?php

namespace Okoye\Core;

class Card extends Base
{

    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('cards');
    }
}
