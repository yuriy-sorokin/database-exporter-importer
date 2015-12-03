<?php
namespace DatabaseExporterImporter\Model\DataProvider;

use DatabaseExporterImporter\Entity\Table;

/**
 * Class TableForeignKeysValuesProvider
 * @package DatabaseExporterImporter\Model\DataProvider
 */
class TableForeignKeysValuesProvider
{
    /**
     * @var \DatabaseExporterImporter\Entity\Table
     */
    private $table;
    /**
     * @var \DatabaseExporterImporter\Entity\Table[]
     */
    private $tables;

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return TableForeignKeysValuesProvider
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table[] $tables
     * @return TableForeignKeysValuesProvider
     */
    public function setTables(array $tables)
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * @return array
     */
    public function getForeignKeyValues()
    {
        $values = [];

        foreach ($this->table->getColumns() as $column) {
            if (null !== $column->getForeignKey()) {
                $values[$column->getName()] = [];

                foreach ($this->tables as $existingTable) {
                    if ($column->getForeignKey()->getTableName() === $existingTable->getName()) {
                        foreach ($existingTable->getDataRows() as $dataRow) {
                            foreach ($dataRow->getDataColumns() as $dataColumn) {
                                if ($dataColumn->getName() === $column->getForeignKey()->getColumnName()) {
                                    $values[$column->getName()][] = $dataColumn->getValue();
                                }
                            }
                        }
                    }
                }
            }
        }

        return $values;
    }
}
