<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataProvider;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\DataColumn;
use DatabaseExporterImporter\Entity\DataRow;
use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataProvider\TableForeignKeysValuesProvider;

/**
 * Class TableForeignKeysValuesProviderTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataProvider
 */
class TableForeignKeysValuesProviderTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $dataRow = new DataRow();
        $dataColumn = new DataColumn('id');
        $dataColumn->setValue(1);
        $dataRow->addDataColumn($dataColumn);

        $dataColumn = new DataColumn('name');
        $dataColumn->setValue('Acura');
        $dataRow->addDataColumn($dataColumn);

        $makerTable = new Table('maker');
        $makerTable
            ->addColumn(new Column('id'))
            ->addColumn(new Column('name'))
            ->addDataRow($dataRow);


        $dataRow = new DataRow();
        $dataColumn = new DataColumn('id');
        $dataColumn->setValue(1);
        $dataRow->addDataColumn($dataColumn);

        $dataColumn = new DataColumn('name');
        $dataColumn->setValue('MDX');
        $dataRow->addDataColumn($dataColumn);

        $dataColumn = new DataColumn('maker_id');
        $dataColumn->setValue(1);
        $dataRow->addDataColumn($dataColumn);

        $foreignKey = new ForeignKey();
        $foreignKey
            ->setTableName('maker')
            ->setColumnName('id');
        $makerIdColumn = new Column('maker_id');
        $makerIdColumn->setForeignKey($foreignKey);
        $modelTable = new Table('model');
        $modelTable
            ->addColumn(new Column('id'))
            ->addColumn(new Column('name'))
            ->addColumn($makerIdColumn)
            ->addDataRow($dataRow);

        $subject = new TableForeignKeysValuesProvider();
        $subject
            ->setTable($modelTable)
            ->setTables([$makerTable, $modelTable]);

        $result = $subject->getForeignKeyValues();
        static::assertTrue(is_array($result));
        static::assertCount(1, $result);
        static::assertArrayHasKey('maker_id', $result);
        static::assertCount(1, $result['maker_id']);
        static::assertSame(1, $result['maker_id'][0]);
    }
}
