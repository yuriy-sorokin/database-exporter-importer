<?php
namespace DatabaseExporterImporter\Model\Column;

use DatabaseExporterImporter\Entity\Table;

/**
 * Class AutoIncrementTableColumnFinder
 * @package DatabaseExporterImporter\Model\Column
 */
class AutoIncrementTableColumnFinder
{
    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return \DatabaseExporterImporter\Entity\Column|null
     */
    public function getAutoIncrementColumn(Table $table)
    {
        foreach ($table->getColumns() as $column) {
            if (true === $column->isAutoIncrement()) {
                return $column;
            }
        }
    }
}
