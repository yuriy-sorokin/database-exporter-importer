<?php
namespace DatabaseExporterImporter\Test\Unit\Entity;

use DatabaseExporterImporter\Entity\DataColumn;

/**
 * Class DataColumnTest
 * @package DatabaseExporterImporter\Test\Unit\Entity
 */
class DataColumnTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new DataColumn('testColumn');
        $subject->setValue('testValue');
        static::assertSame('testColumn', $subject->getName());
        static::assertSame('testValue', $subject->getValue());
        static::assertTrue($subject->isOriginalValue());
        $subject->setOriginalValue(false);
        static::assertFalse($subject->isOriginalValue());
    }
}
