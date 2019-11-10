<?php

namespace System\Database\QueryBuilders;

/**
 * Create queries to execute in MySQL database
 *
 * Class MysqlQueryBuilder
 * @package Database\QueryBuilders
 */
class MysqlQueryBuilder implements QueryBuilderInterface
{
    /**
     * Table where to execute queries
     * @var string|null
     */
    protected $from;

    /**
     * WHERE clause array
     * @var array
     */
    private $where;

    /**
     * ORDER BY clause array
     * @var array
     */
    private $order;

    /**
     * GROUP BY clause array
     * @var array
     */
    private $group;

    /**
     * Compiled query. Read to execute (prepare if using PDO)
     * @var string
     */
    private $query;

    /**
     * Model constructor.
     * @param string|null $from
     */
    public function __construct($from = null)
    {
        $this->flush();
        $this->from = $from;
    }

    /**
     * Compile Query to string
     *
     * @return string
     */
    public function getQuery()
    {
        $query = [$this->query];

        if($this->where) {
            $query[] = 'WHERE ' . implode(' ', $this->where);
        }

        if($this->order) {
            $query[] = 'ORDER BY ' . implode(',', $this->order);
        }

        if($this->group) {
            $query[] = 'GROUP BY ' . implode(',', $this->group);
        }

        return implode(' ', $query);
    }

    /**
     * @inheritDoc
     */
    public function select(array $columns = ['*'])
    {
        $this->query = vsprintf('SELECT %s FROM %s;', [
            implode(',', $columns),
            $this->from
        ]);
    }

    /**
     * @inheritDoc
     */
    public function insert(array $columns)
    {
        $values = [];
        foreach($columns as $column) {
            $values[] = '?';
        }

        $this->query = vsprintf('INSERT INTO %s (%s) VALUES(%s)', [
            $this->from,
            implode(',', $columns),
            implode(',', $values)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function update(array $columns)
    {
        if(!$this->where) {
            throw new \Exception(
                'Cannot create UPDATE Query without "WHERE" clause.'
            );
        }

        foreach($columns as $key => $column) {
            $columns[$key] = $column . ' = ?';
        }

        $this->query = vsprintf('UPDATE %s SET %s', [
            $this->from,
            implode(',', $columns)
        ]);
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if(!$this->where) {
            throw new \Exception(
                'Cannot create DELETE Query without "WHERE" clause.'
            );
        }

        $this->query = sprintf('DELETE FROM %s', $this->from);
    }

    /**
     * Clear all values to reuse Query Builder
     */
    public function flush()
    {
        $this->from = null;
        $this->where = [];
        $this->order = [];
        $this->group = [];
        $this->query = null;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function where(string $clause, string $operator = null)
    {
        if($operator) {
            $this->where[] = $operator;
        }
        $this->where[] = $clause;

        return $this;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function andWhere(string $clause)
    {
        $this->where($clause, 'AND');
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function orWhere(string $clause)
    {
        $this->where($clause, 'OR');
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function orderBy($clause, $sort = 'ASC')
    {
        if(is_array($clause)) {
            foreach($clause AS $order) {
                $this->order[] = $order;
            }
        } else {
            $this->order[] = $clause . ' ' . $sort;
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function groupBy($clause)
    {
        if(is_array($clause)) {
            foreach($clause AS $group) {
                $this->group[] = $group;
            }
        } else {
            $this->group[] = $clause;
        }

        return $this;
    }
}