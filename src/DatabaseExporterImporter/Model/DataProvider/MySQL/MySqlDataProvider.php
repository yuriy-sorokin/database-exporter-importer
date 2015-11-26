<?php
namespace DatabaseExporterImporter\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataProvider\DataProvider;

/**
 * Class MySqlDataProvider
 * @package DatabaseExporterImporter\Model\DataProvider\MySQL
 */
class MySqlDataProvider extends DataProvider
{
    /**
     * @var \PDO
     */
    private $connection;
    /**
     * @var \DatabaseExporterImporter\Entity\Table[]
     */
    private $tables = [];

    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    public function getTables()
    {
        $this
            ->setTables()
            ->setTablesContent();

        return $this->tables;

    }

    /**
     * @throws \RuntimeException
     * @return $this
     */
    private function setTablesContent()
    {
        foreach ($this->tables as $table) {
            foreach ($this->getTableData($table) as $columns) {
                foreach ((array)$columns as $columnName => $columnValue) {
                    $column = new Column($columnName);
                    $table->addColumn($column->setValue($columnValue));
                }
            }
        }

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @throws \RuntimeException
     * @return \PDOStatement
     */
    private function getTableData(Table $table)
    {
        $statement = $this->connection->prepare('SELECT * FROM ' . $table->getName());
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $this->executeStatement($statement);

        return $statement;
    }

    /**
     * @param \PDOStatement $statement
     * @throws \RuntimeException
     */
    private function executeStatement(\PDOStatement $statement)
    {
        $statement->execute();
        $errorInfo = $statement->errorInfo();

        if (null !== $errorInfo[2]) {
            throw new \RuntimeException($errorInfo[2]);
        }
    }

    /**
     * @throws \RuntimeException
     * @return $this
     */
    private function setTables()
    {
        $statement = $this->connection->prepare("SHOW TABLES LIKE '" . $this->tablePrefix . "%'");
        $this->executeStatement($statement);

        foreach ($statement as $item) {
            $this->tables[] = new Table($item[0]);
        }

        return $this;
    }
}
