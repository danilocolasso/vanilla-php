<?php

namespace System\Database\Connection;

/**
 * Interface ConnectionInterface
 * @package Database\Connection
 */
interface ConnectionInterface
{
    /**
     * @param array $config
     * @return \PDO
     */
    public function connect(array $config);

    /**
     * Set query
     * @param string $query
     * @return \PDOStatement
     */
    public function query(string $query);

    /**
     * Prepare the query to execute
     * @param string $query
     * @return \PDOStatement
     */
    public function prepare(string $query);

    /**
     * Bind a param to the query
     * @param string $param
     * @param mixed $value
     * @return bool
     */
    public function bindParam(string $param, $value);

    /**
     * Execute the query
     * @param array|null $data
     * @return bool
     */
    public function execute($data = null);

    /**
     * Fetch data from the executed query (one row each loop)
     * @return array|false
     */
    public function fetch();

    /**
     * Fetch data from the executed query (array of rows each loop)
     * @return array|false
     */
    public function fetchAll();

    /**
     * Returns the number of rows affected by the last SQL statement
     * @return int
     */
    public function rowCount();

    /**
     * Select the last inserted id from current table
     * @return string
     */
    public function lastInsertId();

    /**
     * Clear values
     * @return void
     */
    public function flush();
}