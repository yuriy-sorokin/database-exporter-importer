<?php
namespace DatabaseExporterImporter\Test\Unit\Entity;

use DatabaseExporterImporter\Entity\Column;

/**
 * Class ColumnTest
 * @package DatabaseExporterImporter\Test\Unit\Entity
 */
class ColumnTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new Column('name');
        $subject->setValue('value');
        static::assertSame('name', $subject->getName());
        static::assertSame('value', $subject->getValue());
    }
}
