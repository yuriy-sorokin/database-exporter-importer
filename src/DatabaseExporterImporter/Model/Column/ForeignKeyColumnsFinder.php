<?php
namespace DatabaseExporterImporter\Model\Column;

use DatabaseExporterImporter\Entity\Column;

/**
 * Class ForeignKeyColumnsFinder
 * @package DatabaseExporterImporter\Model\Column
 */
class ForeignKeyColumnsFinder
{
    /**
     * @var \DatabaseExporterImporter\Entity\Column
     */
    private $parentColumn;
    /**
     * @var \DatabaseExporterImporter\Entity\Table[]
     */
    private $tables;

    /**
     * @param \DatabaseExporterImporter\Entity\Table[] $tables
     * @return $this
     */
    public function setTables(array $tables)
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Column $parentColumn
     * @return $this
     */
    public function setParentColumn(Column $parentColumn)
    {
        $this->parentColumn = $parentColumn;

        return $this;
    }

    /**
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Column[]
     */
    public function getDependentColumns()
    {
        if (null === $this->parentColumn->getTable()) {
            throw new \RuntimeException('The parent column does not contain a parent table.');
        }

        $columns = [];

        foreach ($this->tables as $table) {
            foreach ($table->getColumns() as $column) {
                if (null !== $column->getForeignKey() &&
                    $column->getForeignKey()->getColumnName() === $this->parentColumn->getName() &&
                    $column->getForeignKey()->getTableName() === $this->parentColumn->getTable()->getName()
                ) {
                    $columns[] = $column;
                }
            }
        }

        return $columns;
    }
}
