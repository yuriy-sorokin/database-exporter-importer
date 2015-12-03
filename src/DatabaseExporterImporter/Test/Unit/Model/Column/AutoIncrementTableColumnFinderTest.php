<?php
namespace DatabaseExporterImporter\Test\Unit\Model\Column;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\Column\AutoIncrementTableColumnFinder;

/**
 * Class AutoIncrementTableColumnFinderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\Column
 */
class AutoIncrementTableColumnFinderTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $table = new Table('table_name');
        $column1 = new Column('id');
        $column1->setAutoIncrement(true);
        $column2 = new Column('name');
        $table
            ->addColumn($column1)
            ->addColumn($column2);

        $subject = new AutoIncrementTableColumnFinder();
        static::assertSame($column1->getName(), $subject->getAutoIncrementColumn($table)->getName());
        static::assertNull($subject->getAutoIncrementColumn(new Table('test_table')));
    }
}
