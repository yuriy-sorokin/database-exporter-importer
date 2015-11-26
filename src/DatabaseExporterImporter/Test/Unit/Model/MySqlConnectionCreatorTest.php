<?php
namespace DatabaseExporterImporter\Test\Unit\Model;

use DatabaseExporterImporter\Model\MySqlConnectionCreator;

/**
 * Class MySqlConnectionCreatorTest
 * @package DatabaseExporterImporter\Test\Unit\Model
 */
class MySqlConnectionCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new MySqlConnectionCreator();
        static::assertInstanceOf(\PDO::class, $subject->getConnection());
    }
}
