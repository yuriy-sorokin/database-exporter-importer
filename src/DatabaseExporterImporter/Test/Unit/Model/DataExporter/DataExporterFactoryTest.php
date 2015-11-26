<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataExporter;

use DatabaseExporterImporter\Model\DataExporter\DataExporterFactory;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonDataExporter;

/**
 * Class DataExporterFactoryTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataExporter
 */
class DataExporterFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new DataExporterFactory();
        static::assertInstanceOf(JsonDataExporter::class, $subject->createDataJsonExporter());
    }
}
