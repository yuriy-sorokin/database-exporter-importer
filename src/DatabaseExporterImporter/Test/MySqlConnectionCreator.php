<?php
namespace DatabaseExporterImporter\Test;

/**
 * Class MySqlConnectionCreator
 * @package DatabaseExporterImporter\Test
 */
class MySqlConnectionCreator
{
    /**
     * @return \PDO
     */
    public function createConnection()
    {
        return new \PDO('mysql:dbname=exporter_importer;host=localhost', 'root', '1q2w3e');
    }
}
