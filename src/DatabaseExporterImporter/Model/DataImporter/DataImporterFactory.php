<?php
namespace DatabaseExporterImporter\Model\DataImporter;

use DatabaseExporterImporter\Model\MySqlConnectionCreator;
use DatabaseExporterImporter\Model\DataImporter\MySQL\MySqlDataImporter;

/**
 * Class DataImporterFactory
 * @package DatabaseExporterImporter\Model\DataImporter
 */
class DataImporterFactory
{
    /**
     * @return \DatabaseExporterImporter\Model\DataImporter\MySQL\MySqlDataImporter
     */
    public function createMySqlDataImporter()
    {
        $connection = new MySqlConnectionCreator();

        return new MySqlDataImporter($connection->getConnection());
    }
}
