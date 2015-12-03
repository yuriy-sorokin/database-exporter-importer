<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataExporter\JSON;

use DatabaseExporterImporter\Entity\DataColumn;
use DatabaseExporterImporter\Entity\DataRow;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableDataRowsExporter;

/**
 * Class JsonTableDataRowsExporterTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataExporter\JSON
 */
class JsonTableDataRowsExporterTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $dataColumn = $this->getMock(DataColumn::class, [], ['column_name']);
        $dataColumn->method('getName')->willReturn('column_name');
        $dataColumn->method('getValue')->willReturn('column_value');

        $dataRow = $this->getMock(DataRow::class);
        $dataRow->method('getDataColumns')->willReturn([$dataColumn]);

        $table = $this->getMock(Table::class, [], ['table_name']);
        $table->method('getDataRows')->willReturn([$dataRow]);

        $subject = new JsonTableDataRowsExporter();
        $result = $subject->getDataRows($table);
        static::assertTrue(is_array($result));
        static::assertCount(1, $result);
        $firstElement = reset($result);
        static::assertArrayHasKey('column_name', $firstElement);
        static::assertSame('column_value', $firstElement['column_name']);
    }
}
