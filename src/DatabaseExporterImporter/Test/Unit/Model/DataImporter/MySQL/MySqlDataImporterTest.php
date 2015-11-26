<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataImporter\MySQL;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataImporter\MySQL\MySqlDataImporter;
use DatabaseExporterImporter\Model\DataParser\DataParser;
use DatabaseExporterImporter\Test\MySqlConnectionCreator;

/**
 * Class MySqlDataImporterTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataImporter\MySQL
 */
class MySqlDataImporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var int
     */
    private $makerName;

    public function test()
    {
        $this->makerName = mt_rand();
        $column1Mock = $this->getMock(Column::class, null, ['name']);
        $column1Mock->setValue($this->makerName);
        $column2Mock = $this->getMock(Column::class, null, ['external_id']);
        $column2Mock->setValue(mt_rand());
        $tableMock = $this->getMock(Table::class, ['getColumns'], ['maker']);
        $tableMock->expects(static::exactly(2))->method('getColumns')->willReturn([$column1Mock, $column2Mock]);
        $dataParser = $this->getMock(DataParser::class);
        $dataParser->expects(static::exactly(2))->method('getTables')->willReturn([$tableMock]);

        $connection = new MySqlConnectionCreator();
        $subject = new MySqlDataImporter($connection->getConnection());
        $subject
            ->setDataParser($dataParser)
            ->import();

        $this->setExpectedException(\RuntimeException::class);
        $subject->import();
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        parent::tearDown();

        $connectionCreator = new MySqlConnectionCreator();
        $statement = $connectionCreator->getConnection()->prepare('DELETE FROM maker WHERE name = :name');
        $statement->execute([':name' => $this->makerName]);
    }
}
