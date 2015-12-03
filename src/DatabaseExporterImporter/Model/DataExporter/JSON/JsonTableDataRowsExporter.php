<?php
namespace DatabaseExporterImporter\Model\DataExporter\JSON;

use DatabaseExporterImporter\Entity\Table;

/**
 * Class JsonTableDataRowsExporter
 * @package DatabaseExporterImporter\Model\DataExporter\JSON
 */
class JsonTableDataRowsExporter
{
    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return array
     */
    public function getDataRows(Table $table)
    {
        $dataRows = [];

        foreach ($table->getDataRows() as $dataRow) {
            $row = [];

            foreach ($dataRow->getDataColumns() as $dataColumn) {
                $row[$dataColumn->getName()] = $dataColumn->getValue();
            }

            $dataRows[] = $row;
        }

        return $dataRows;
    }
}
