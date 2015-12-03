<?php
namespace DatabaseExporterImporter\Model\DataExporter\JSON;

use DatabaseExporterImporter\Model\DataExporter\DataExporter;

/**
 * Class JsonDataExporter
 * @package DatabaseExporterImporter\Model\DataExporter\JSON
 */
class JsonDataExporter extends DataExporter
{
    /**
     * @var \DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableColumnsExporter
     */
    private $columnsExporter;
    /**
     * @var \DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableDataRowsExporter
     */
    private $dataRowsExporter;

    /**
     * @param \DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableColumnsExporter $columnsExporter
     * @return $this
     */
    public function setColumnsExporter(JsonTableColumnsExporter $columnsExporter)
    {
        $this->columnsExporter = $columnsExporter;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableDataRowsExporter $dataRowsExporter
     * @return $this
     */
    public function setDataRowsExporter(JsonTableDataRowsExporter $dataRowsExporter)
    {
        $this->dataRowsExporter = $dataRowsExporter;

        return $this;
    }

    /**
     * @throws \LogicException
     * @throws \RuntimeException
     * @return string
     */
    public function getData()
    {
        if (null === $this->dataProvider) {
            throw new \LogicException('Please, set a data provider.');
        }

        $data = [];

        foreach ($this->dataProvider->getTables() as $table) {
            $data[$table->getName()] = [
                'columns'   => $this->columnsExporter->getTableColumns($table),
                'data_rows' => $this->dataRowsExporter->getDataRows($table)
            ];
        }

        return json_encode($data);
    }
}
