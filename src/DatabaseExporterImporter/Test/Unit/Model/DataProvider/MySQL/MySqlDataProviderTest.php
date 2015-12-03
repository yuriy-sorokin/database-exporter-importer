<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL;

use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTableColumnsProvider;
use DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlTablesProvider;
use DatabaseExporterImporter\Model\DataProvider\TableForeignKeysValuesProvider;
use DatabaseExporterImporter\Test\MySqlConnectionCreator;

/**
 * Class MySqlDataProviderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataProvider\MySQL
 */
class MySqlDataProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PDO
     */
    private $connection;

    public function testAllTables()
    {
        $tables = $this->getSubject()->getTables();
        static::assertTrue(is_array($tables));

        $statement = $this->connection->query('SELECT COUNT(*) FROM maker');
        $statement->setFetchMode(\PDO::FETCH_NUM);
        $makersAmount = (int)reset($statement->fetch());

        foreach ($tables as $table) {
            if ('maker' === $table->getName()) {
                static::assertCount($makersAmount, $table->getDataRows());

                break;
            }
        }
    }

    /**
     * @return \DatabaseExporterImporter\Model\DataProvider\MySQL\MySqlDataProvider
     */
    private function getSubject()
    {
        $connectionCreator = new MySqlConnectionCreator();
        $this->connection = $connectionCreator->createConnection();
        $columnsProvider = new MySqlTableColumnsProvider($this->connection);
        $tablesProvider = new MySqlTablesProvider($columnsProvider);
        $tablesProvider->setConnection($this->connection);

        $subject = new MySqlDataProvider($tablesProvider);

        return $subject->setConnection($this->connection);
    }

    public function testDependentTable()
    {
        $tables = $this->getSubject()
                       ->setPrimaryTableName('maker')
                       ->setPrimaryKey(1)
                       ->setPrimaryKeyColumn('id')
                       ->setForeignValueProvider(new TableForeignKeysValuesProvider())
                       ->getTables();
        static::assertTrue(is_array($tables));
        static::assertCount(2, $tables);
    }

    public function testQueryError()
    {
        $statement = $this->getMock(\PDOStatement::class, [], [], '', false);
        $statement->method('errorInfo')->willReturn(['', '', 'Error message']);
        $connection = $this->getMock(\PDO::class, [], [], '', false);
        $connection->method('prepare')->willReturn($statement);

        $subject = $this->getSubject();
        $subject->setConnection($connection);

        $this->setExpectedExceptionRegExp(\RuntimeException::class, '/Error message/');
        $subject->getTables();
    }
}
