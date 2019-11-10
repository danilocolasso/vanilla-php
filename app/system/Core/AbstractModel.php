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
     * @var \System\Database\QueryBuilders\QueryBuilderInterface
     */
    protected $queryBuilder;

    /**
     * Connection constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->switchDriver();
    }

    /**
     * @throws \Exception
     */
    protected function switchDriver()
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

                //Query Builder Singleton
                if(!$this->queryBuilder){
                    $this->queryBuilder = new MysqlQueryBuilder();
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
}