<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataExporter\JSON;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataExporter\JSON\JsonTableColumnsExporter;

/**
 * Class JsonTableColumnsExporterTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataExporter\JSON
 */
class JsonTableColumnsExporterTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $foreignKey = $this->getMock(ForeignKey::class);
        $foreignKey->method('getTableName')->willReturn('maker');
        $foreignKey->method('getColumnName')->willReturn('id');

        $column = $this->getMock(Column::class, [], ['column_name']);
        $column->method('getName')->willReturn('column_name');
        $column->method('isAutoIncrement')->willReturn(true);
        $column->method('getForeignKey')->willReturn($foreignKey);

        $table = $this->getMock(Table::class, [], ['table_name']);
        $table->method('getColumns')->willReturn([$column]);

        $subject = new JsonTableColumnsExporter();
        $result = $subject->getTableColumns($table);
        static::assertTrue(is_array($result));
        static::assertCount(1, $result);
        $firstElement = reset($result);
        static::assertSame('column_name', $firstElement['name']);
        static::assertSame('1', (string)$firstElement['auto_increment']);
        static::assertSame('maker', $firstElement['foreign_key']['table_name']);
        static::assertSame('id', $firstElement['foreign_key']['column_name']);
    }
}
