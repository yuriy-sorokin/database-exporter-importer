<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataImporter\MySQL;

use DatabaseExporterImporter\Model\Column\AutoIncrementTableColumnFinder;
use DatabaseExporterImporter\Model\Column\ForeignKeyColumnsFinder;
use DatabaseExporterImporter\Model\DataImporter\MySQL\MySqlDataImporter;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator;
use DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementObserver;
use DatabaseExporterImporter\Test\MySqlConnectionCreator;

/**
 * Class MySqlDataImporterTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataImporter\MySQL
 */
class MySqlDataImporterTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $data = json_encode([
                                'maker' => [
                                    'columns'   => [
                                        ['name' => 'id', 'auto_increment' => 1],
                                        ['name' => 'name']
                                    ],
                                    'data_rows' => [
                                        ['id' => 1, 'name' => 'Maker 1'],
                                        ['id' => 2, 'name' => 'Maker 2']
                                    ]
                                ],
                                'model' => [
                                    'columns'   => [
                                        ['name' => 'id', 'auto_increment' => 1],
                                        [
                                            'name'        => 'maker_id',
                                            'foreign_key' => ['table_name' => 'maker', 'column_name' => 'id']
                                        ],
                                        ['name' => 'name']
                                    ],
                                    'data_rows' => [
                                        ['id' => 3, 'maker_id' => 1, 'name' => 'Model 1'],
                                        ['id' => 4, 'maker_id' => 1, 'name' => 'Model 2']
                                    ]
                                ]
                            ]);
        $dataParser = new JsonDataParser();
        $dataParser
            ->setColumnsCreator(new JsonColumnsCreator())
            ->setDataRowsCreator(new JsonDataRowsCreator())
            ->setData($data);

        $connection = new MySqlConnectionCreator();
        $subject = new MySqlDataImporter();
        $subject
            ->setConnection($connection->createConnection())
            ->setDataParser($dataParser)
            ->setObserver(new AutoIncrementObserver(new ForeignKeyColumnsFinder()))
            ->setAutoIncrementFinder(new AutoIncrementTableColumnFinder())
            ->import();

        $connectionCreator = new MySqlConnectionCreator();
        $connection = $connectionCreator->createConnection();

        $statement = $connection->query("SELECT * FROM maker WHERE name IN('Maker 1', 'Maker 2') ORDER BY name");
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $makers = $statement->fetchAll();
        static::assertSame('Maker 1', $makers[0]['name']);
        static::assertNotSame(1, (int)$makers[0]['id']);

        static::assertSame('Maker 2', $makers[1]['name']);
        static::assertNotSame(2, (int)$makers[1]['id']);

        $statement = $connection->query(
            'SELECT * FROM model WHERE maker_id IN(' . implode(', ', [$makers[0]['id'], $makers[1]['id']]) . ') ' .
            'ORDER BY name'
        );
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $models = $statement->fetchAll();
        static::assertSame('Model 1', $models[0]['name']);
        static::assertNotSame(3, (int)$models[0]['id']);

        static::assertSame('Model 2', $models[1]['name']);
        static::assertNotSame(4, (int)$models[1]['id']);

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
        $connection = $connectionCreator->createConnection();
        $connection->query('SET FOREIGN_KEY_CHECKS = 0');
        $connection->query(
            'DELETE maker, model FROM maker LEFT JOIN model ON maker.id = model.maker_id ' .
            "WHERE maker.name IN('Maker 1', 'Maker 2')"
        );
        $connection->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}
