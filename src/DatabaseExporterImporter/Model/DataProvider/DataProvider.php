<?php
namespace DatabaseExporterImporter\Model\DataProvider;

/**
 * Class DataProvider
 * @package DatabaseExporterImporter\Model\DataProvider
 */
abstract class DataProvider
{
    /**
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    abstract public function getTables();

    /**
     * @param string $tablePrefix
     * @return $this
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;

        return $this;
    }
}
