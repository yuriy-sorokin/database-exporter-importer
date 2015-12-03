<?php
namespace DatabaseExporterImporter\Test\Unit\Entity;

use DatabaseExporterImporter\Entity\ForeignKey;

/**
 * Class ForeignKeyTest
 * @package DatabaseExporterImporter\Test\Unit\Entity
 */
class ForeignKeyTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new ForeignKey();
        $subject
            ->setTableName('table1')
            ->setColumnName('column1');

        static::assertSame('table1', $subject->getTableName());
        static::assertSame('column1', $subject->getColumnName());
    }
}
