<?php

namespace Okoye\Core;

class Category extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('categories');
    }

    public function jobsCount($id)
    {
        $job = new Job;

        return count($job->getAll("industry = $id"));
    }
}
