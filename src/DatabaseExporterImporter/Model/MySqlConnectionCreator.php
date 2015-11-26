<?php
namespace DatabaseExporterImporter\Model;

/**
 * Class MySqlConnectionCreator
 * @package DatabaseExporterImporter\Model
 */
class MySqlConnectionCreator
{
    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return new \PDO('mysql:dbname=exporter_importer;host=localhost', 'root', '1q2w3e');
    }
}
