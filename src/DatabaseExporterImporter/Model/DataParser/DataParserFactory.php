<?php
namespace DatabaseExporterImporter\Model\DataParser;

use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;

/**
 * Class DataParserFactory
 * @package DatabaseExporterImporter\Model\DataParser
 */
class DataParserFactory
{
    /**
     * @return \DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser
     */
    public function createJsonDataParser()
    {
        return new JsonDataParser();
    }
}
