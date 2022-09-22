<?php

namespace Okoye\Core;

class Notification extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('notifications');
    }
}
