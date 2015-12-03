<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator;

/**
 * Class JsonDataParserTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON
 */
class JsonDataParserTest extends \PHPUnit_Framework_TestCase
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
        $subject = new JsonDataParser();
        $subject
            ->setColumnsCreator(new JsonColumnsCreator())
            ->setDataRowsCreator(new JsonDataRowsCreator())
            ->setData($data);
        $tables = $subject->getTables();

        static::assertTrue(is_array($tables));
        static::assertCount(2, $tables);

        $this
            ->checkMakerTableResult($tables[0])
            ->checkModelTableResult($tables[1]);

    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return $this
     */
    private function checkModelTableResult(Table $table)
    {
        static::assertInstanceOf(Table::class, $table);
        static::assertSame('model', $table->getName());
        $columns = $table->getColumns();
        static::assertCount(3, $columns);
        static::assertSame('id', $columns['id']->getName());
        static::assertTrue($columns['id']->isAutoIncrement());
        static::assertInstanceOf(ForeignKey::class, $columns['maker_id']->getForeignKey());
        static::assertSame('maker', $columns['maker_id']->getForeignKey()->getTableName());
        static::assertSame('id', $columns['maker_id']->getForeignKey()->getColumnName());

        $dataRows = $table->getDataRows();
        static::assertCount(2, $dataRows);
        $dataColumns = $dataRows[0]->getDataColumns();
        static::assertCount(3, $dataColumns);
        static::assertSame('id', $dataColumns[0]->getName());
        static::assertSame(3, $dataColumns[0]->getValue());

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return $this
     */
    private function checkMakerTableResult(Table $table)
    {
        static::assertInstanceOf(Table::class, $table);
        static::assertSame('maker', $table->getName());
        $columns = $table->getColumns();
        static::assertCount(2, $columns);
        static::assertSame('id', $columns['id']->getName());
        static::assertTrue($columns['id']->isAutoIncrement());

        $dataRows = $table->getDataRows();
        static::assertCount(2, $dataRows);
        $dataColumns = $dataRows[0]->getDataColumns();
        static::assertCount(2, $dataColumns);
        static::assertSame('id', $dataColumns[0]->getName());
        static::assertSame(1, $dataColumns[0]->getValue());

        return $this;
    }

    public function testIncorrectJsonString()
    {
        $subject = new JsonDataParser('{a=>b}');
        $this->setExpectedExceptionRegExp(\InvalidArgumentException::class, '/The data must represent a json string/');
        $subject->getTables();
    }

    public function testIncorrectTableStructure()
    {
        $subject = new JsonDataParser();
        $subject->setData(json_encode(['table' => 'string']));
        $this->setExpectedExceptionRegExp(\InvalidArgumentException::class, '/The data structure is incorrect/');
        $subject->getTables();
    }

    public function testIncorrectTableColumnsStructure()
    {
        $subject = new JsonDataParser();
        $subject->setData(json_encode(['table' => ['abc']]));
        $this->setExpectedExceptionRegExp(
            \InvalidArgumentException::class,
            '/The data structure is incorrect: \'columns\' key is absent/'
        );
        $subject->getTables();
    }
}
