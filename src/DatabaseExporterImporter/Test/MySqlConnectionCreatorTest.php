<?php
namespace DatabaseExporterImporter\Test;

/**
 * Class MySqlConnectionCreatorTest
 * @package DatabaseExporterImporter\Test
 */
class MySqlConnectionCreatorTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $subject = new MySqlConnectionCreator();
        static::assertInstanceOf(\PDO::class, $subject->createConnection());
    }
}
