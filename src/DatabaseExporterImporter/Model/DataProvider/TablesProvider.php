<?php
namespace DatabaseExporterImporter\Model\DataProvider;

use DatabaseExporterImporter\Entity\Table;

/**
 * Class TablesProvider
 * @package DatabaseExporterImporter\Model\DataProvider
 */
abstract class TablesProvider
{
    /**
     * @var \DatabaseExporterImporter\Model\DataProvider\TableColumnsProvider
     */
    protected $columnsProvider;
    /**
     * @var \DatabaseExporterImporter\Entity\Table
     */
    protected $rootTableName;

    /**
     * @param \DatabaseExporterImporter\Model\DataProvider\TableColumnsProvider $columnsProvider
     */
    public function __construct(TableColumnsProvider $columnsProvider)
    {
        $this->columnsProvider = $columnsProvider;
    }

    /**
     * @param string $rootTableName
     * @return TablesProvider
     */
    public function setRootTableName($rootTableName)
    {
        $this->rootTableName = $rootTableName;

        return $this;
    }

    /**
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    public function getTables()
    {
        $tables = [];

        if (null !== $this->rootTableName) {
            $tables[] = $this->createTable($this->rootTableName);
        }

        return array_merge($tables, $this->getDependentTables($this->rootTableName));
    }

    /**
     * @param string $tableName
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Table
     */
    protected function createTable($tableName)
    {
        $table = new Table($tableName);

        foreach ($this->columnsProvider->setTable($table)->getTableColumns() as $tableColumn) {
            $table->addColumn($tableColumn);
        }

        return $table;
    }

    /**
     * @param string $parentTableName
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    abstract protected function getDependentTables($parentTableName);
}
