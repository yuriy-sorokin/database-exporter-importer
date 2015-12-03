<?php
namespace DatabaseExporterImporter\Test\Unit\Entity;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Entity\Table;

/**
 * Class ColumnTest
 * @package DatabaseExporterImporter\Test\Unit\Entity
 */
class ColumnTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $foreignKey = new ForeignKey();
        $foreignKey
            ->setTableName('table1')
            ->setColumnName('column1');
        $subject = new Column('name');
        $subject
            ->setForeignKey($foreignKey)
            ->setTable(new Table('test_table'));
        static::assertSame('name', $subject->getName());
        static::assertInstanceOf(ForeignKey::class, $subject->getForeignKey());

        $subject->removeForeignKey();
        static::assertSame(null, $subject->getForeignKey());

        static::assertFalse($subject->isAutoIncrement());
        $subject->setAutoIncrement(true);
        static::assertTrue($subject->isAutoIncrement());

        static::assertSame('test_table', $subject->getTable()->getName());
    }
}
