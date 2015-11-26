<?php
namespace DatabaseExporterImporter\Model\DataImporter\MySQL;

use DatabaseExporterImporter\Model\DataImporter\DataImporter;

/**
 * Class MySqlDataImporter
 * @package DatabaseExporterImporter\Model\DataImporter\MySQL
 */
class MySqlDataImporter extends DataImporter
{
    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @param \PDO $connection
     */
    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function import()
    {
        foreach ($this->dataParser->getTables() as $table) {
            $sql = 'INSERT INTO ' . $table->getName();
            $sqlColumns = [];
            $sqlValues = [];

            foreach ($table->getColumns() as $column) {
                $sqlColumns[] = $column->getName();
                $sqlValues[] = $column->getValue();
            }

            $sql .= ' (' . implode(', ', $sqlColumns) . ')';
            $sql .= ' VALUES(' . substr(str_repeat('?, ', count($sqlValues)), 0, -2) . ')';

            $statement = $this->connection->prepare($sql);

            if (false === $statement->execute($sqlValues)) {
                $errors = $statement->errorInfo();
                throw new \RuntimeException($errors[2]);
            }
        }
    }
}
