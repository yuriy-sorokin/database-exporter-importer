<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataImporter;

use DatabaseExporterImporter\Model\DataImporter\DataImporterFactory;
use DatabaseExporterImporter\Model\DataImporter\MySQL\MySqlDataImporter;

/**
 * Class DataImporterFactoryTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataImporter
 */
class DataImporterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new DataImporterFactory();
        static::assertInstanceOf(MySqlDataImporter::class, $subject->createMySqlDataImporter());
    }
}
