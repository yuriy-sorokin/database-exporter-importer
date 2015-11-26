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
     * @throws \LogicException
     * @return string
     */
    public function getData()
    {
        if (null === $this->dataProvider) {
            throw new \LogicException('Please, set a data provider.');
        }

        $data = [];

        foreach ($this->dataProvider->getTables() as $table) {
            $data[$table->getName()] = [];

            foreach ($table->getColumns() as $column) {
                $data[$table->getName()][$column->getName()] = $column->getValue();
            }
        }

        return json_encode($data);
    }
}
