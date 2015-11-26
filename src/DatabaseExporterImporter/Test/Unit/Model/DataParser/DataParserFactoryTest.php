<?php
namespace DatabaseExporterImporter\Test\Unit\Model\DataParser;

use DatabaseExporterImporter\Model\DataParser\DataParserFactory;
use DatabaseExporterImporter\Model\DataParser\JSON\JsonDataParser;

/**
 * Class DataParserFactoryTest
 * @package DatabaseExporterImporter\Test\Unit\Model\DataParser
 */
class DataParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new DataParserFactory();
        static::assertInstanceOf(JsonDataParser::class, $subject->createJsonDataParser());
    }
}
