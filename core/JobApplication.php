<?php

namespace Okoye\Core;

class JobApplication extends Base
{
    public function __construct($table = null)
    {
        ($table) ? $this->table = $table : parent::__construct('job_applications');
    }

    /**
     * Get data by Ref
     *
     * @param int $ref
     *
     * @return array|bool
     */
    public function getByRef($ref)
    {
        Database::instance()->query("SELECT * FROM {$this->table} WHERE ref = :ref");
        Database::instance()->bind(':ref', $ref);
        Database::instance()->execute();

        return (Database::instance()->rowCount() > 0) ? Database::instance()->fetchAll()[0] : false;
    }
}
