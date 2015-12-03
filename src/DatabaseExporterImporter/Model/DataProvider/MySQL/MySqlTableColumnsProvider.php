<?php
namespace DatabaseExporterImporter\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Model\DataProvider\TableColumnsProvider;

/**
 * Class MySqlTableColumnsProvider
 * @package DatabaseExporterImporter\Model\DataProvider\MySQL
 */
class MySqlTableColumnsProvider extends TableColumnsProvider
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
     * @inheritdoc
     */
    protected function setForeignKeys()
    {
        $statement = $this->connection->prepare(
            'SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME ' .
            'FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = :table AND REFERENCED_COLUMN_NAME IS NOT NULL'
        );
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute([':table' => $this->table->getName()]);

        foreach ($statement as $item) {
            foreach ($this->columns as $column) {
                if ($column->getName() === $item['COLUMN_NAME']) {
                    $foreignKey = new ForeignKey();
                    $column->setForeignKey(
                        $foreignKey
                            ->setTableName($item['REFERENCED_TABLE_NAME'])
                            ->setColumnName($item['REFERENCED_COLUMN_NAME'])
                    );
                }
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function setColumns()
    {
        $statement = $this->connection->prepare('DESC ' . $this->table->getName());
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->execute();

        foreach ($statement as $item) {
            $column = new Column($item['Field']);
            $this->columns[] = $column->setAutoIncrement('auto_increment' === $item['Extra']);
        }

        return $this;
    }
}
