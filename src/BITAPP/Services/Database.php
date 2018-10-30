<?php declare(strict_types=1);

namespace BITAPP\Services;

use \BITAPP\AbstractManager;

/**
 * @method static Database get()
 */
class Database extends AbstractManager
{
    protected static $instance;

    /**
     * @var \PDO $connHander
     */
    private $connHander;

    /**
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function init() : void
    {
        $this->connect();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    private function connect() : void
    {
        $dbconfig = Config::get()->getConfig('DB');
        $mysql_dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $dbconfig['host'],
            $dbconfig['port'],
            $dbconfig['dbname'],
            $dbconfig['charset']
        );
        try {
            $this->connHander = new \PDO($mysql_dsn, $dbconfig['username'], $dbconfig['password']);
        } catch (\PDOException $ex) {
            throw new \RuntimeException($ex->getMessage());
        }
        $this->connHander->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->connHander->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->connHander->setAttribute(\PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
    }

    /**
     * @return \PDO
     */
    public function getConnHandler() : \PDO
    {
        return $this->connHander;
    }

    /**
     * @param string $query
     * @throws \RuntimeException
     */
    public function execute(string $query) : void
    {
        $statement = $this->connHander->prepare($query);
        $res = $statement->execute();
        if (!$res) {
            throw new \RuntimeException(
                'Failed exequte query "' . $query . '".'
            );
        }
    }

    /**
     * @param string $query
     * @return mixed
     * @throws \RuntimeException
     */
    public function getScalar(string $query) : mixed
    {
        $statement = $this->connHander->prepare($query);
        $res = $statement->execute();
        if (!$res) {
            throw new \RuntimeException(
                'Query "' . $query . '" does not return data.'
            );
        }
        $result = $statement->fetch(\PDO::FETCH_NUM);
        if (empty($result)) {
            throw new \RuntimeException(
                'Query "' . $query . '" does not return data.'
            );
        }
        return $result[0];
    }

    /**
     * @param string $query
     * @return array
     * @throws \RuntimeException
     */
    public function getResult(string $query) : array
    {
        $statement = $this->connHander->prepare($query);
        $res = $statement->execute();
        if (!$res) {
            throw new \RuntimeException(
                'Query "' . $query . '" does not return data.'
            );
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function beginTransaction() : void
    {
        $this->connHander->beginTransaction();
    }

    public function rollBack() : void
    {
        $this->connHander->rollBack();
    }

    public function commit() : void
    {
        $this->connHander->commit();
    }

    public function inTransaction() : bool
    {
        return $this->connHander->inTransaction();
    }
}
