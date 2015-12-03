<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\ForeignKey;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator;

/**
 * Class JsonColumnsCreatorTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON
 */
class JsonColumnsCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $subject = new JsonColumnsCreator();
        $result = $subject->getColumns([
                                              ['name' => 'id', 'auto_increment' => 1],
                                              [
                                                  'name'        => 'maker_id',
                                                  'foreign_key' => ['table_name' => 'maker', 'column_name' => 'id']
                                              ]
                                          ]);
        static::assertCount(2, $result);
        static::assertSame('id', $result[0]->getName());
        static::assertTrue($result[0]->isAutoIncrement());

        static::assertSame('maker_id', $result[1]->getName());
        static::assertFalse($result[1]->isAutoIncrement());
        static::assertInstanceOf(ForeignKey::class, $result[1]->getForeignKey());
        static::assertSame('maker', $result[1]->getForeignKey()->getTableName());
        static::assertSame('id', $result[1]->getForeignKey()->getColumnName());
    }

    public function testIncorrectColumnDataStructure()
    {
        $subject = new JsonColumnsCreator();
        $this->setExpectedException(\PHPUnit_Framework_Error::class);
        $subject->getColumns(['abc']);
    }

    public function testMissingColumnName()
    {
        $subject = new JsonColumnsCreator();
        $this->setExpectedExceptionRegExp(
            \InvalidArgumentException::class,
            "/The column data structure is incorrect: 'name' index is missing/"
        );
        $subject->getColumns([['abc']]);
    }
}
