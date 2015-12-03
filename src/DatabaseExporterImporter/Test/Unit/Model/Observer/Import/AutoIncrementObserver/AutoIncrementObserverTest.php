<?php
namespace DatabaseExporterImporter\Test\Unit\Model\Observer\Import\AutoIncrementObserver;

use DatabaseExporterImporter\Model\Column\ForeignKeyColumnsFinder;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator;
use DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementChangeEvent;
use DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementObserver;

/**
 * Class AutoIncrementObserverTest
 * @package DatabaseExporterImporter\Test\Unit\Model\AutoIncrementObserver\Import\AutoIncrementObserver
 */
class AutoIncrementObserverTest extends \PHPUnit_Framework_TestCase
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
                                        ['id' => 4, 'maker_id' => 1, 'name' => 'Model 2'],
                                        ['id' => 5, 'maker_id' => 2, 'name' => 'Model 3']
                                    ]
                                ]
                            ]);
        $subject = new JsonDataParser();
        $subject
            ->setColumnsCreator(new JsonColumnsCreator())
            ->setDataRowsCreator(new JsonDataRowsCreator())
            ->setData($data);
        $tables = $subject->getTables();
        $makerColumns = $tables[0]->getColumns();

        $event = new AutoIncrementChangeEvent();
        $event
            ->setColumn($makerColumns['id'])
            ->setOldValue(1)
            ->setNewValue(456);

        $subject = new AutoIncrementObserver(new ForeignKeyColumnsFinder());
        $subject
            ->setTables($tables)
            ->notify($event);

        $changedValues = 0;

        foreach ($tables[1]->getDataRows() as $dataRow) {
            foreach ($dataRow->getDataColumns() as $dataColumn) {
                if ('maker_id' === $dataColumn->getName() && 2 !== $dataColumn->getValue()) {
                    static::assertFalse($dataColumn->isOriginalValue());
                    static::assertSame(456, $dataColumn->getValue());
                    $changedValues++;
                }
            }
        }

        static::assertSame(2, $changedValues);
    }
}
