<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataExporter\JSON;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\DataColumn;
use DatabaseExporterImporter\Entity\DataRow;
use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonDataExporter;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableColumnsExporter;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableDataRowsExporter;
use DatabaseExporterImporter\Model\DataProvider\DataProvider;

/**
 * Class JsonDataExporterTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataExporter
 */
class JsonDataExporterTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $foreignKey = $this->getMock(ForeignKey::class);
        $foreignKey->method('getTableName')->willReturn('maker');
        $foreignKey->method('getColumnName')->willReturn('id');

        $columnMockId = $this->getMock(Column::class, [], ['id']);
        $columnMockId->method('getName')->willReturn('id');

        $columnMockName = $this->getMock(Column::class, [], ['name']);
        $columnMockName->method('getName')->willReturn('name');

        $columnMockMakerId = $this->getMock(Column::class, [], ['maker_id']);
        $columnMockMakerId->method('getName')->willReturn('maker_id');
        $columnMockMakerId->method('getForeignKey')->willReturn($foreignKey);

        $dataColumnId = $this->getMock(DataColumn::class, [], ['id']);
        $dataColumnId->method('getName')->willReturn('id');
        $dataColumnId->method('getValue')->willReturn(1);

        $dataColumnName = $this->getMock(DataColumn::class, [], ['name']);
        $dataColumnName->method('getName')->willReturn('name');
        $dataColumnName->method('getValue')->willReturn('MDX');

        $dataColumnMakerId = $this->getMock(DataColumn::class, [], ['maker_id']);
        $dataColumnMakerId->method('getName')->willReturn('maker_id');
        $dataColumnMakerId->method('getValue')->willReturn(1);

        $dataRow = $this->getMock(DataRow::class);
        $dataRow->method('getDataColumns')->willReturn([$dataColumnId, $dataColumnName, $dataColumnMakerId]);

        $tableName = 'model';
        $tableMock = $this->getMock(Table::class, [], [$tableName]);
        $tableMock->method('getName')->willReturn($tableName);
        $tableMock->method('getColumns')->willReturn([$columnMockId, $columnMockName, $columnMockMakerId]);
        $tableMock->method('getDataRows')->willReturn([$dataRow]);

        $dataProviderMock = $this->getMock(DataProvider::class, [], [], '', false);
        $dataProviderMock->method('getTables')->willReturn([$tableMock]);
        $subject = new JsonDataExporter();
        $data = $subject
            ->setColumnsExporter(new JsonTableColumnsExporter())
            ->setDataRowsExporter(new JsonTableDataRowsExporter())
            ->setDataProvider($dataProviderMock)->getData();

        static::assertJson($data);
        $parsedData = json_decode($data, true);
        static::assertTrue(is_array($parsedData));
        static::assertGreaterThan(0, count($parsedData));
        static::assertArrayHasKey($tableName, $parsedData);
        static::assertGreaterThan(0, count($parsedData[$tableName]));
        static::assertArrayHasKey('columns', $parsedData[$tableName]);
        static::assertArrayHasKey('data_rows', $parsedData[$tableName]);
        static::assertCount(3, $parsedData[$tableName]['columns']);
        static::assertCount(3, $parsedData[$tableName]['columns']);
        static::assertSame('maker_id', $parsedData[$tableName]['columns'][2]['name']);
        static::assertSame('maker', $parsedData[$tableName]['columns'][2]['foreign_key']['table_name']);
        static::assertSame('id', $parsedData[$tableName]['columns'][2]['foreign_key']['column_name']);
    }

    public function testAbsentDataProvider()
    {
        $subject = new JsonDataExporter();
        $this->setExpectedExceptionRegExp(\LogicException::class, '/Please, set a data provider/');
        $subject->getData();
    }
}
