<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider;
use DatabaseExporterImporter\Test\MySqlConnectionCreator;

/**
 * Class MySqlDataProviderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL
 */
class MySqlDataProviderTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $connection = new MySqlConnectionCreator();
        $subject = new MySqlDataProvider($connection->getConnection());
        $tables = $subject->getTables();
        static::assertTrue(is_array($tables));
        static::assertGreaterThan(0, count($tables));
    }

    public function testQueryFailure()
    {
        $connection = new MySqlConnectionCreator();
        $subject = new MySqlDataProvider($connection->getConnection());
        $subject->setTablePrefix('\\34#$#$\maker"`\'');
        $this->setExpectedException(\RuntimeException::class);
        $subject->getTables();
    }
}
