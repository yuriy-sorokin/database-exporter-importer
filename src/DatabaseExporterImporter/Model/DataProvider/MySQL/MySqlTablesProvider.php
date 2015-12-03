<?php
namespace DatabaseExporterImporter\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Model\DataProvider\TablesProvider;

/**
 * Class MySqlTablesProvider
 * @package DatabaseExporterImporter\Model\DataProvider\MySQL
 */
class MySqlTablesProvider extends TablesProvider
{
    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @param \PDO $connection
     * @return MySqlTablesProvider
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @param string|null $parentTableName
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    protected function getDependentTables($parentTableName = null)
    {
        if (null !== $parentTableName) {
            $this->checkTableExistence($parentTableName);
        }

        $tables = [];

        foreach ($this->getDatabaseTables($parentTableName) as $databaseTable) {
            $tables[] = $this->createTable($databaseTable[0]);

            if (null !== $parentTableName) {
                foreach ($this->getDependentTables($databaseTable[0]) as $dependentTable) {
                    $tables[] = $dependentTable;
                }
            }
        }

        return $tables;
    }

    /**
     * @param string $tableName
     * @throws \RuntimeException
     */
    private function checkTableExistence($tableName)
    {
        $statement = $this->connection->prepare('DESC ' . $tableName);
        $statement->execute();
        $errorInfo = $statement->errorInfo();

        if (null !== $errorInfo[2]) {
            throw new \RuntimeException($errorInfo[2]);
        }
    }

    /**
     * @param string|null $parentTableName
     * @return \PDOStatement
     */
    private function getDatabaseTables($parentTableName = null)
    {
        $query = 'SHOW TABLES';
        $queryParams = [];

        if (null !== $parentTableName) {
            $query = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_NAME = :table';
            $queryParams = [':table' => $parentTableName];
        }

        $statement = $this->connection->prepare($query);
        $statement->execute($queryParams);
        $statement->setFetchMode(\PDO::FETCH_NUM);

        return $statement;
    }
}
