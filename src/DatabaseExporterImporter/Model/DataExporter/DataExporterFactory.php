<?php
namespace DatabaseExporterImporter\Model\DataExporter;

use DatabaseExporterImporter\Model\DataExporter\JSON\JsonDataExporter;

/**
 * Class DataExporterFactory
 * @package DatabaseExporterImporter\Model\DataExporter
 */
class DataExporterFactory
{
    /**
     * @return \DatabaseExporterImporter\Model\DataExporter\JSON\JsonDataExporter
     */
    public function createDataJsonExporter()
    {
        return new JsonDataExporter();
    }
}
