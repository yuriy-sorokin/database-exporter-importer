<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataProvider;

use DatabaseExporterImporter\Model\DataProvider\DataProviderFactory;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider;

/**
 * Class DataProviderFactoryTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataProvider
 */
class DataProviderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new DataProviderFactory();
        static::assertInstanceOf(MySqlDataProvider::class, $subject->createMySqlDataProvider());
    }
}
