<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTableColumnsProvider;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTablesProvider;
use DatabaseExporterImporter\Test\MySqlConnectionCreator;

/**
 * Class MySqlTablesProviderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL
 */
class MySqlTablesProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testAllTables()
    {
        $connectionCreator = new MySqlConnectionCreator();
        $connection = $connectionCreator->createConnection();
        $columnsProvider = new MySqlTableColumnsProvider($connection);
        $subject = new MySqlTablesProvider($columnsProvider);
        $subject->setConnection($connection);
        $tables = $subject->getTables();
        static::assertCount(6, $tables);
        $firstElement = reset($tables);
        static::assertInstanceOf(Table::class, $firstElement);
        static::assertSame('city', $firstElement->getName());
    }

    public function testRootTable()
    {
        $connectionCreator = new MySqlConnectionCreator();
        $connection = $connectionCreator->createConnection();
        $columnsProvider = new MySqlTableColumnsProvider($connection);
        $subject = new MySqlTablesProvider($columnsProvider);
        $tables = $subject
            ->setConnection($connection)
            ->setRootTableName('state')
            ->getTables();
        static::assertCount(3, $tables);
    }

    public function testNonExistingTable()
    {
        $connectionCreator = new MySqlConnectionCreator();
        $connection = $connectionCreator->createConnection();
        $columnsProvider = new MySqlTableColumnsProvider($connection);
        $subject = new MySqlTablesProvider($columnsProvider);
        $subject
            ->setConnection($connection)
            ->setRootTableName('maker123');
        $this->setExpectedException(\RuntimeException::class);
        $subject->getTables();
    }
}
