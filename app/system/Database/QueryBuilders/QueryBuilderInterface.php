<?php

namespace System\Database\QueryBuilders;

/**
 * Interface QueryBuilderInterface
 * @package Database\QueryBuilders
 */
interface QueryBuilderInterface
{
    /**
     * Create select query
     *
     * @param array $columns
     * @return void
     */
    public function select(array $columns = ['*']);

    /**
     * Create Insert query
     *
     * @param array $columns
     * @return void
     */
    public function insert(array $columns);

    /**
     * Create Update query
     *
     * @param array $columns
     * @return void
     */
    public function update(array $columns);

    /**
     * Create Delete query
     * @return void
     */
    public function delete();

    /**
     * Add WHERE clause to query
     *
     * @param string $clause
     * @param string|null $operator
     */
    public function where(string $clause, string $operator = null);

    /**
     * Add AND WHERE to query
     *
     * @param string $clause
     * @return self
     */
    public function andWhere(string $clause);

    /**
     * Add OR WHERE to query
     *
     * @param string $clause
     * @return self
     */
    public function orWhere(string $clause);

    /**
     * Add ORDER BY to query
     *
     * @param array|string $clause
     * @param string $sort
     * @return self
     */
    public function orderBy($clause, $sort = 'ASC');

    /**
     * Add GROUP BY to query
     *
     * @param array|string $clause
     * @return self
     */
    public function groupBy($clause);
}