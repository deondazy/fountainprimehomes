<?php

namespace Okoye\Core;

class Country extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('countries');
    }
}
