<?php
namespace DatabaseExporterImporter\Test\Unit\Entity;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;

/**
 * Class TableTest
 * @package DatabaseExporterImporter\Test\Unit\Entity
 */
class TableTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $tableName = mt_rand();
        $subject = new Table($tableName);
        static::assertSame($tableName, $subject->getName());
        static::assertCount(0, $subject->getColumns());

        $columnName = mt_rand();
        $columnValue = mt_rand();
        $column = new Column($columnName);
        $column->setValue($columnValue);
        $subject->addColumn($column);

        static::assertCount(1, $subject->getColumns());
        $firstColumn = reset($subject->getColumns());
        static::assertSame($columnName, $firstColumn->getName());
        static::assertSame($columnValue, $firstColumn->getValue());
    }
}
