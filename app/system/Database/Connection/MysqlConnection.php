<?php

namespace System\Database\Connection;

/**
 * Class MysqlConnection
 * @package Database\Connection
 */
class MysqlConnection implements ConnectionInterface
{
    /**
     * @var \PDO
     */
    protected $connection;

    /**
     * @var \PDOStatement
     */
    protected $stmt;

    /**
     * {@inheritdoc}
     */
    public function connect (array $config)
    {
        $base = $config['base'];
        $host = $config['host'];
        $port = $config['port'];

        $dsn 	 = "mysql:dbname=$base;host=$host;port=$port";
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => true
        ];

        try {
            $this->connection = new \PDO($dsn, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function query(string $query)
    {
        $this->stmt = $this->connection->query($query);

        return $this->stmt;
    }

    /**
     * @inheritDoc
     */
    public function prepare(string $query)
    {
        $this->stmt = $this->connection->prepare($this);

        return $this->stmt;
    }

    /**
     * @inheritDoc
     */
    public function bindParam(string $param, $value)
    {
        $this->stmt->bindParam($param, $value);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function execute($data = null)
    {
        return $this->stmt->execute($data);
    }

    /**
     * @inheritDoc
     */
    public function fetch()
    {
        return $this->stmt->fetch();
    }

    /**
     * @inheritDoc
     */
    public function fetchAll()
    {
        return $this->stmt->fetchAll();
    }

    /**
     * @inheritDoc
     */
    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * @inheritDoc
     */
    public function flush()
    {
        $this->stmt = null;
    }
}