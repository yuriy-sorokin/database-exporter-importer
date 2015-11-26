<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;

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
                                    [
                                        'id'          => 1,
                                        'name'        => mt_rand(),
                                        'external_id' => mt_rand(),
                                        'new'         => mt_rand()
                                    ],
                                    [
                                        'id'          => 2,
                                        'name'        => mt_rand(),
                                        'external_id' => mt_rand(),
                                        'new'         => mt_rand()
                                    ]
                                ]
                            ]);
        $subject = new JsonDataParser();
        $subject->setData($data);
        $tables = $subject->getTables();
        static::assertTrue(is_array($tables));
        $firstTable = reset($tables);
        static::assertInstanceOf(Table::class, $firstTable);
        static::assertCount(2, $firstTable->getColumns());
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
        $this->setExpectedExceptionRegExp(
            \InvalidArgumentException::class,
            '/The data structure is incorrect: table columns must be an array/'
        );
        $subject->getTables();
    }
}
