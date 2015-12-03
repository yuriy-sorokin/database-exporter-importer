<?php
namespace DatabaseExporterImporter\Model\DataExporter\JSON;

use DatabaseExporterImporter\Entity\Table;

/**
 * Class JsonTableColumnsExporter
 * @package DatabaseExporterImporter\Model\DataExporter\JSON
 */
class JsonTableColumnsExporter
{
    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return array
     */
    public function getTableColumns(Table $table)
    {
        $columns = [];

        foreach ($table->getColumns() as $column) {
            $columnExport = ['name' => $column->getName()];

            if (true === $column->isAutoIncrement()) {
                $columnExport['auto_increment'] = true;
            }

            $foreignKey = $column->getForeignKey();

            if (null !== $foreignKey) {
                $columnExport['foreign_key'] =
                    ['table_name' => $foreignKey->getTableName(), 'column_name' => $foreignKey->getColumnName()];
            }

            $columns[] = $columnExport;
        }

        return $columns;
    }
}
