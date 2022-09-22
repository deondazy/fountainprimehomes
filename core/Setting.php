<?php

namespace Okoye\Core;

class Setting
{
    /**
     * Database options table
     *
     * @var string
     */
    protected $table;

    public function __construct($table = 'settings')
    {
        $this->table = $table;
    }

    /**
     * Get a setting value from database settings table
     *
     * @param string $setting
     * @return string
     */
    public function get($setting)
    {
        Database::instance()->query(
            "SELECT value FROM {$this->table} WHERE name = :setting"
        );

        Database::instance()->bind(':setting', $setting);

        return Database::instance()->fetchOne()->value;
    }

    /**
     * Update a setting in Database settings table
     *
     * @param string $setting
     * @param string $value
     * @return int
     */
    public function set($setting, $value)
    {
        $stmt = Database::instance()->query(
            "UPDATE {$this->table} SET value = :value WHERE name = :setting"
        );
        $stmt->bindValue(':value', $value);
        $stmt->bindValue(':setting', $setting);

        return $stmt->execute();
    }

    /**
     * Add new setting to settings table
     *
     * @param string $setting
     * @param string $value
     * @return int
     */
    public function add($setting, $value)
    {
        Database::instance()->query(
            "INSERT INTO {$this->table} (name, value) VALUES (:setting, :value)"
        );
        Database::instance()->bind(':setting', $setting);
        Database::instance()->bind(':value', $value);
        Database::instance()->execute();

        return Database::instance()->rowCount();
    }
}
