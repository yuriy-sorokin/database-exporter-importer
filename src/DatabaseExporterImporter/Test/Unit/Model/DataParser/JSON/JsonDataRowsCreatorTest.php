<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\DataRow;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator;

/**
 * Class JsonDataRowsCreatorTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON
 */
class JsonDataRowsCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $subject = new JsonDataRowsCreator();
        $result = $subject->getDataRows([['id' => 1, 'name' => 'Maker 1'], ['id' => 2, 'name' => 'Maker 2']]);
        static::assertTrue(is_array($result));
        static::assertCount(2, $result);
        static::assertInstanceOf(DataRow::class, $result[0]);

        $dataColumns = $result[0]->getDataColumns();
        static::assertTrue(is_array($dataColumns));
        static::assertCount(2, $dataColumns);

        static::assertSame('id', $dataColumns[0]->getName());
        static::assertSame(1, $dataColumns[0]->getValue());
        static::assertSame('name', $dataColumns[1]->getName());
        static::assertSame('Maker 1', $dataColumns[1]->getValue());
    }
}
