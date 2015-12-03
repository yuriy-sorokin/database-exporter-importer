<?php
namespace DatabaseExporterImporter\Model\DataProvider\MySQL;

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
     * @param \PDO $connection
     * @return $this
     */
    public function setConnection(\PDO $connection)
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @throws \RuntimeException
     * @return array
     */
    protected function getTableData(Table $table)
    {
        $sql = 'SELECT * FROM ' . $table->getName() . ' WHERE ';
        $sqlAndParts = ['1 = 1'];
        $queryParams = [];

        if (null !== $this->primaryTableName) {
            if ($table->getName() === $this->primaryTableName) {
                $sqlAndParts[] = $this->primaryKeyColumn . ' = :value';
                $queryParams[':value'] = $this->primaryKey;
            } else {
                foreach ($this->foreignValueProvider->setTable($table)->setTables($this->tables)
                                                    ->getForeignKeyValues() as $columnName => $columnValues) {
                    $conditionValuesStubs = [];

                    foreach ((array)$columnValues as $counter => $columnValue) {
                        $conditionValuesStubs[] = ':' . $columnName . $counter;
                        $queryParams[':' . $columnName . $counter] = $columnValue;
                    }

                    $sqlAndParts[] = $columnName . ' IN(' . implode(', ', $conditionValuesStubs) . ')';
                }
            }
        }

        return $this->getQueryResult($sql . implode(' AND ', $sqlAndParts), $queryParams);
    }

    /**
     * @param string $query
     * @param array  $queryParams
     * @throws \RuntimeException
     * @return array
     */
    private function getQueryResult($query, array $queryParams)
    {
        $statement = $this->connection->prepare($query);
        $statement->execute($queryParams);
        $errors = $statement->errorInfo();

        if (null !== $errors[2]) {
            throw new \RuntimeException($errors[2]);
        }

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
