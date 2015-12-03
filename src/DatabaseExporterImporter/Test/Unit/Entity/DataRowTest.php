<?php
namespace DatabaseExporterImporter\Test\Unit\Entity;

use DatabaseExporterImporter\Entity\DataColumn;
use DatabaseExporterImporter\Entity\DataRow;

/**
 * Class DataRowTest
 * @package DatabaseExporterImporter\Test\Unit\Entity
 */
class DataRowTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $dataColumn = new DataColumn('testColumn');
        $dataColumn->setValue('testValue');

        $subject = new DataRow();
        static::assertCount(0, $subject->getDataColumns());
        $subject->addDataColumn($dataColumn);
        static::assertCount(1, $subject->getDataColumns());
    }
}
