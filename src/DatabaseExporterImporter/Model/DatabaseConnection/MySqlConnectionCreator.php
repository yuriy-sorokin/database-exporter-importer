<?php
namespace DatabaseExporterImporter\Model\DatabaseConnection;

/**
 * Class MySqlConnectionCreator
 * @package DatabaseExporterImporter\Model
 */
class MySqlConnectionCreator
{
    /**
     * @var string
     */
    private $database;
    /**
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $password;
    /**
     * @var string
     */
    private $username;

    /**
     * @param string $host
     * @return MySqlConnectionCreator
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param string $database
     * @return MySqlConnectionCreator
     */
    public function setDatabase($database)
    {
        $this->database = $database;

        return $this;
    }

    /**
     * @param string $username
     * @return MySqlConnectionCreator
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param string $password
     * @return MySqlConnectionCreator
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return \PDO
     */
    public function createConnection()
    {
        return new \PDO('mysql:dbname=' . $this->database . ';host=' . $this->host, $this->username, $this->password);
    }
}
