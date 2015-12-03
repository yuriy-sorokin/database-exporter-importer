<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DatabaseConnection;

use DatabaseExporterImporter\Model\DatabaseConnection\MySqlConnectionCreator;

/**
 * Class MySqlConnectionCreatorTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DatabaseConnection
 */
class MySqlConnectionCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new MySqlConnectionCreator();
        $subject
            ->setHost('localhost')
            ->setDatabase('exporter_importer')
            ->setUsername('root')
            ->setPassword('1q2w3e');
        static::assertInstanceOf(\PDO::class, $subject->createConnection());
    }
}
