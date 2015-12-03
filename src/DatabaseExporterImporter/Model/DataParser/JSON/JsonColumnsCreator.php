<?php
namespace DatabaseExporterImporter\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\ForeignKey;

/**
 * Class JsonColumnsCreator
 * @package DatabaseExporterImporter\Model\DataParser\JSON
 */
class JsonColumnsCreator
{
    /**
     * @var \DatabaseExporterImporter\Entity\Column
     */
    private $column;

    /**
     * @param array $columnsData
     * @throws \InvalidArgumentException
     * @return \DatabaseExporterImporter\Entity\Column[]
     */
    public function getColumns(array $columnsData)
    {
        $columns = [];

        foreach ($columnsData as $columnData) {
            $this
                ->createColumn($columnData)
                ->addAutoIncrement($columnData)
                ->addForeignKey($columnData);

            $columns[] = $this->column;
        }

        return $columns;
    }

    /**
     * @param array $columnData
     * @return $this
     */
    private function addForeignKey(array $columnData)
    {
        if (true === array_key_exists('foreign_key', $columnData)) {
            $foreignKey = new ForeignKey();
            $foreignKey
                ->setTableName($columnData['foreign_key']['table_name'])
                ->setColumnName($columnData['foreign_key']['column_name']);
            $this->column->setForeignKey($foreignKey);
        }

        return $this;
    }

    /**
     * @param array $columnData
     * @return $this
     */
    private function addAutoIncrement(array $columnData)
    {
        if (true === array_key_exists('auto_increment', $columnData)) {
            $this->column->setAutoIncrement((bool)$columnData['auto_increment']);
        }

        return $this;
    }

    /**
     * @param array $columnData
     * @throws \InvalidArgumentException
     * @return $this
     */
    private function createColumn(array $columnData)
    {
        if (false === array_key_exists('name', $columnData)) {
            throw new \InvalidArgumentException("The column data structure is incorrect: 'name' index is missing.");
        }

        $this->column = new Column($columnData['name']);

        return $this;
    }
}
