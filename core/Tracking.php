<?php

namespace Okoye\Core;

class Tracking extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('trackings');
    }
}
