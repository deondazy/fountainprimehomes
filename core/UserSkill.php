<?php

namespace Okoye\Core;

class UserSkill extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('user_skills');
    }
}
