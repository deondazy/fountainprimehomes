<?php

namespace Okoye\Core;

class UserEducation extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('user_education');
    }
}
