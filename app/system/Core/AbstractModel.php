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
     * @param $table
     * @return MysqlQueryBuilder
     */
    public function createQueryBuilder($table)
    {
        return new MysqlQueryBuilder($table);
    }

    /**
     * Find All registers
     * @param string $table table name
     * @return array
     * @throws \Exception
     */
    public function findAll($table)
    {
        $qb = new MysqlQueryBuilder($table);

        return $this->db->query($qb->getQuery())->fetchAll();
    }
}