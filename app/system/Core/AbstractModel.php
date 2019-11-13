<?php

namespace System\Core;

use System\Database\Connection\MysqlConnection;
use System\Database\QueryBuilders\MysqlQueryBuilder;

/**
 * Class Model
 * @package Core
 */
abstract class AbstractModel
{
    /**
     * @var \System\Database\Connection\ConnectionInterface
     */
    protected $db;

    /**
     * @var string
     */
    protected $table;

    /**
     * Connection constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $parameters = yaml_parse_file(
            __DIR__ . '../../../config/parameters.yml'
        );

        switch (strtolower($parameters['database']['driver'])) {
            case 'mysql':
                //Connection Singleton
                if(!$this->db) {
                    $this->db = new MysqlConnection();
                    $this->db->connect($parameters['database']);
                }
                break;

            default:
                throw new \Exception(sprintf(
                    'There is no driver for %s',
                    '"' .$parameters['database']['driver'] .  '"'
                ));
                break;
        }
    }

    /**
     * Create a new MySQL Query Builder
     * @param string|null $table
     * @return MysqlQueryBuilder
     */
    public function createQueryBuilder($table = null)
    {
        return new MysqlQueryBuilder($table ? $table : $this->table);
    }

    /**
     * Find All registers
     * @return array
     * @throws \Exception
     */
    public function findAll()
    {
        $qb = new MysqlQueryBuilder($this->table);

        return $this->db->query($qb->getQuery())->fetchAll();
    }

    /**
     * Find one register by id
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function find($id)
    {
        $qb = new MysqlQueryBuilder($this->table);

        $query = $qb
            ->where('id = :id')
            ->getQuery()
        ;

        $this->db->prepare($query)->execute(['id' => (int) $id]);

        return $this->db->fetch();
    }
}