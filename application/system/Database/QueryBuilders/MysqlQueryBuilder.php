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
     * JOIN clause array
     * @var array
     */
    private $join;

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
     * @throws \Exception
     * @return string
     */
    public function getQuery()
    {
        if(strpos($this->query, 'DELETE') === 0 && !$this->where) {
            throw new \Exception(
                'Cannot create UPDATE Query without "WHERE" clause.'
            );
        }

        if(strpos($this->query, 'UPDATE') === 0 && !$this->where) {
            throw new \Exception(
                'Cannot create UPDATE Query without "WHERE" clause.'
            );
        }

        if(!$this->from) {
            throw new \Exception('No table set.');
        }

        if(!$this->query) {
            $this->select();
        }

        $query = [$this->query];

        if($this->join) {
            $query[] = ' ' . implode(' ', $this->join);
        }

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
     * @return MysqlQueryBuilder
     */
    public function select(array $columns = ['*'])
    {
        $this->query = vsprintf('SELECT %s FROM %s', [
            implode(',', $columns),
            $this->from
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function update(array $columns)
    {
        foreach($columns as $key => $column) {
            $columns[$key] = $column . ' = ?';
        }

        $this->query = vsprintf('UPDATE %s SET %s', [
            $this->from,
            implode(',', $columns)
        ]);

        return $this;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
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

        return $this;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function delete()
    {
        $this->query = sprintf('DELETE FROM %s', $this->from);

        return $this;
    }

    /**
     * Clear all values to reuse Query Builder
     */
    public function flush()
    {
        $this->from  = null;
        $this->where = [];
        $this->join  = [];
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
        return $this->where($clause, 'AND');
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function orWhere(string $clause)
    {
        return $this->where($clause, 'OR');
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

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function join($table, $condition, $type = null)
    {
        if($type) {
            $this->join[] = strtolower($type) == 'inner'
                ? 'INNER JOIN'
                : 'LEFT JOIN'
            ;
        } else {
            $this->join[] = 'JOIN';
        }

        $this->join[] = $table;
        $this->join[] = 'ON';
        $this->join[] = $condition;

        return $this;
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function innerJoin($table, $condition)
    {
        return $this->join($table, $condition, 'INNER');
    }

    /**
     * @inheritDoc
     * @return MysqlQueryBuilder
     */
    public function leftJoin($table, $condition)
    {
        return $this->join($table, $condition, 'LEFT');
    }
}