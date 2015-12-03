<?php
namespace DatabaseExporterImporter\Test\Unit\Model\Observer\Import\AutoIncrementObserver;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementChangeEvent;

/**
 * Class AutoIncrementChangeEventTest
 * @package DatabaseExporterImporter\Test\Unit\Model\AutoIncrementObserver\Import\AutoIncrementObserver
 */
class AutoIncrementChangeEventTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new AutoIncrementChangeEvent();
        $subject
            ->setColumn(new Column('id'))
            ->setOldValue(1)
            ->setNewValue(2);
        static::assertSame('id', $subject->getColumn()->getName());
        static::assertSame(1, $subject->getOldValue());
        static::assertSame(2, $subject->getNewValue());
    }
}
