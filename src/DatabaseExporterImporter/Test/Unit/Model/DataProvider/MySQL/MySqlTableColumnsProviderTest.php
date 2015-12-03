<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTableColumnsProvider;
use DatabaseExporterImporter\Test\MySqlConnectionCreator;

/**
 * Class MySqlTableColumnsProviderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL
 */
class MySqlTableColumnsProviderTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $connectionCreator = new MySqlConnectionCreator();
        $subject = new MySqlTableColumnsProvider($connectionCreator->createConnection());
        $columns = $subject
            ->setTable(new Table('model'))
            ->getTableColumns();

        static::assertGreaterThan(0, count($columns));
        static::assertInstanceOf(Column::class, $columns[0]);
        static::assertSame('id', $columns[0]->getName());
        static::assertTrue($columns[0]->isAutoIncrement());
        static::assertFalse($columns[1]->isAutoIncrement());
    }

    public function testTableNameNotSet()
    {
        $connectionCreator = new MySqlConnectionCreator();
        $subject = new MySqlTableColumnsProvider($connectionCreator->createConnection());
        $this->setExpectedExceptionRegExp(\RuntimeException::class, '/Please, provide a target table/');
        $subject->getTableColumns();
    }
}
