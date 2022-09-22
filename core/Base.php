<?php

namespace Okoye\Core;

abstract class Base
{
    /**
     * The Database Table;
     *
     * @var string
     */
    protected $table;

    /**
     * Constructor sets the Database Table.
     *
     * @param string $table
     */
    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * Create a new record in the Database
     *
     * @param array $data The data ['column' => 'value'] to insert.
     *
     * @return int The number of inserted rows.
     */
    public function create(array $data)
    {
        return Database::instance()->insert($this->table, $data);
    }

    /**
     * Get a Value from a specific Column.
     *
     * @param string $field The column to get.
     * @param int $id The id of the value to get.
     *
     * @return mixed
     */
    public function get($field, $id)
    {
        return Database::instance()->get($this->table, $field, $id);
    }

    /**
     * Get all entry from all columns
     *
     * @param string $where The WHERE clause for query.
     * @param string $order ORDER BY.
     *
     * @return array
     */
    public function getAll($where = null, $order = null, $limit = null)
    {
        return Database::instance()->getAll($this->table, $where, $order, $limit);
    }

    /**
     * Update a value in a column
     *
     * @param array $data ['colum' => 'value'] for update
     * @param int $id The id of the field to update
     *
     * @return int Number of updated columns
     */
    public function update(array $data, $id)
    {
        return Database::instance()->update($this->table, $data, $id);
    }

    /**
     * Delete a value from a table's column
     *
     * @param int $id The id of the value to delete
     *
     * @return int
     */
    public function delete($id)
    {
        return Database::instance()->delete($this->table, $id);
    }

    /*
     * Count all rows in the table
     *
     * @return int
     */
    public function count()
    {
        return count($this->getAll());
    }

    /*
     * Get the last inserted id
     *
     * @return int
     */
    public function lastInsertId()
    {
        return Database::instance()->lastInsertId();
    }

    /**
     * Get data by id
     * 
     * @param  int $id
     * 
     * @return array|bool
     */
    public function getById($id)
    {
        Database::instance()->query("SELECT * FROM {$this->table} WHERE id = :id");
        Database::instance()->bind(':id', $id);
        Database::instance()->execute();

        return (Database::instance()->rowCount() > 0) ? Database::instance()->fetchAll()[0] : false;
    }
    
    /**
     * Get data by slug
     *
     * @param int $slug
     *
     * @return array|bool
     */
    public function getBySlug($slug)
    {
        Database::instance()->query("SELECT * FROM {$this->table} WHERE slug = :slug");
        Database::instance()->bind(':slug', $slug);
        Database::instance()->execute();

        return (Database::instance()->rowCount() > 0) ? Database::instance()->fetchAll()[0] : false;
    }

    /**
     * Search
     * 
     * @param string $term
     * 
     * @return array|bool
     */
    public function search($term)
    {
        $keywords = preg_split('/[\s]+/', $term);
        $totalKeywords = count($keywords);
        $sqlStr = "title LIKE :search0 ";
        
        for($i = 1; $i < $totalKeywords; $i++) {
            $searchBit = ":search" . $i;
            $sqlStr .= " OR title LIKE $searchBit ";
        }
        
        $sql = "SELECT *
                FROM {$this->table}
                WHERE {$sqlStr}
                ORDER BY create_date";
                
        Database::instance()->query($sql);
        
        foreach ($keywords as $key => $keyword) {
            $s = "%{$keyword}%";
            Database::instance()->bind(':search' . $key, $s);
        }
        
        Database::instance()->execute();

        return (Database::instance()->rowCount() > 0) ? Database::instance()->fetchAll() : false;
    }
}
