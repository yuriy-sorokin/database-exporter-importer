<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataExporter\JSON;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonDataExporter;
use DatabaseExporterImporter\Model\DataProvider\DataProvider;

/**
 * Class JsonDataExporterTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataExporter
 */
class JsonDataExporterTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $columnName = mt_rand();
        $columnValue = mt_rand();
        $columnMock = $this->getMock(Column::class, null, [$columnName]);
        $columnMock->setValue($columnValue);
        $tableName = mt_rand();
        $tableMock = $this->getMock(Table::class, ['getColumns'], [$tableName]);
        $tableMock->expects(static::once())->method('getColumns')->willReturn([$columnMock]);
        $dataProviderMock = $this->getMock(DataProvider::class);
        $dataProviderMock->expects(static::once())->method('getTables')->willReturn([$tableMock]);
        $subject = new JsonDataExporter();
        $data = $subject
            ->setDataProvider($dataProviderMock)
            ->getData();

        static::assertJson($data);
        $parsedData = json_decode($data, true);
        static::assertTrue(is_array($parsedData));
        static::assertGreaterThan(0, count($parsedData));
        static::assertArrayHasKey($tableName, $parsedData);
        static::assertGreaterThan(0, count($parsedData[$tableName]));
        static::assertArrayHasKey($columnName, $parsedData[$tableName]);
        static::assertSame($columnValue, $parsedData[$tableName][$columnName]);
    }

    public function testAbsentDataProvider()
    {
        $subject = new JsonDataExporter();
        $this->setExpectedExceptionRegExp(\LogicException::class, '/Please, set a data provider/');
        $subject->getData();
    }
}
