<?php
namespace DatabaseExporterImporter\Model\DataImporter\MySQL;

use DatabaseExporterImporter\Entity\Table;
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
     * @return $this
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table        $table
     * @param \DatabaseExporterImporter\Entity\DataColumn[] $values
     * @return int
     * @throws \RuntimeException
     */
    protected function insertRecord(Table $table, array $values)
    {
        $sqlValues = [];

        foreach ($values as $value) {
            $sqlValues[$value->getName()] = $value->getValue();
        }

        $statement = $this->connection->prepare(
            'INSERT INTO ' . $table->getName() . ' (' . implode(', ', array_keys($sqlValues)) . ') ' .
            'VALUES(' . substr(str_repeat('?, ', count($sqlValues)), 0, -2) . ')'
        );

        if (false === $statement->execute(array_values($sqlValues))) {
            $errors = $statement->errorInfo();
            throw new \RuntimeException($errors[2]);
        }

        return $this->connection->lastInsertId();
    }
}
