<?php
namespace DatabaseExporterImporter\Model\DataProvider;

use DatabaseExporterImporter\Model\MySqlConnectionCreator;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider;

/**
 * Class DataProviderFactory
 * @package DatabaseExporterImporter\Model\DataProvider
 */
class DataProviderFactory
{
    /**
     * @return \DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider
     */
    public function createMySqlDataProvider()
    {
        $connection = new MySqlConnectionCreator();

        return new MySqlDataProvider($connection->getConnection());
    }
}
