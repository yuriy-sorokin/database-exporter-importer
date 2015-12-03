<?php
namespace DatabaseExporterImporter\Test\Unit\Model\Column;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Model\Column\ForeignKeyColumnsFinder;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator;

/**
 * Class ForeignKeyColumnsFinderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\Column
 */
class ForeignKeyColumnsFinderTest extends \PHPUnit_Framework_TestCase
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
        $tables = $dataParser->getTables();
        $columns = $tables[0]->getColumns();

        $subject = new ForeignKeyColumnsFinder();
        $result = $subject
            ->setTables($tables)
            ->setParentColumn($columns['id'])
            ->getDependentColumns();
        static::assertTrue(is_array($result));
        static::assertCount(1, $result);
        static::assertSame('maker_id', $result[0]->getName());
        static::assertSame('model', $result[0]->getTable()->getName());

        $subject->setParentColumn(new Column('test_column'));
        $this->setExpectedExceptionRegExp(
            \RuntimeException::class,
            '/The parent column does not contain a parent table/'
        );
        $subject->getDependentColumns();
    }
}
