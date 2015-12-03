<?php
namespace DatabaseExporterImporter\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\DataColumn;
use DatabaseExporterImporter\Entity\DataRow;

/**
 * Class JsonDataRowsCreator
 * @package DatabaseExporterImporter\Model\DataParser\JSON
 */
class JsonDataRowsCreator
{
    /**
     * @param array $rowsData
     * @return \DatabaseExporterImporter\Entity\DataRow[]
     */
    public function getDataRows(array $rowsData)
    {
        $dataRows = [];

        foreach ($rowsData as $rowData) {
            $dataRow = new DataRow();

            foreach ($rowData as $columnName => $columnValue) {
                $dataColumn = new DataColumn($columnName);
                $dataRow->addDataColumn($dataColumn->setValue($columnValue));
            }

            $dataRows[] = $dataRow;
        }

        return $dataRows;
    }
}
