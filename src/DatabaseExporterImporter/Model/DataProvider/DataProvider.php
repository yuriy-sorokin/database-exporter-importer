<?php
namespace DatabaseExporterImporter\Model\DataProvider;

use DatabaseExporterImporter\Entity\DataColumn;
use DatabaseExporterImporter\Entity\DataRow;
use DatabaseExporterImporter\Entity\Table;

/**
 * Class DataProvider
 * @package DatabaseExporterImporter\Model\DataProvider
 */
abstract class DataProvider
{
    /**
     * @var \DatabaseExporterImporter\Model\DataProvider\TableForeignKeysValuesProvider
     */
    protected $foreignValueProvider;
    /**
     * @var mixed
     */
    protected $primaryKey;
    /**
     * @var string
     */
    protected $primaryKeyColumn;
    /**
     * @var string
     */
    protected $primaryTableName;
    /**
     * @var \DatabaseExporterImporter\Entity\Table[]
     */
    protected $tables = [];
    /**
     * @var \DatabaseExporterImporter\Model\DataProvider\TablesProvider
     */
    protected $tablesProvider;

    /**
     * @param \DatabaseExporterImporter\Model\DataProvider\TablesProvider $tablesProvider
     */
    public function __construct(TablesProvider $tablesProvider)
    {
        $this->tablesProvider = $tablesProvider;
    }

    /**
     * @param \DatabaseExporterImporter\Model\DataProvider\TableForeignKeysValuesProvider $foreignValueProvider
     * @return $this
     */
    public function setForeignValueProvider(TableForeignKeysValuesProvider $foreignValueProvider)
    {
        $this->foreignValueProvider = $foreignValueProvider;

        return $this;
    }

    /**
     * @param string $primaryKeyColumn
     * @return DataProvider
     */
    public function setPrimaryKeyColumn($primaryKeyColumn)
    {
        $this->primaryKeyColumn = $primaryKeyColumn;

        return $this;
    }

    /**
     * @param string $primaryTableName
     * @return DataProvider
     */
    public function setPrimaryTableName($primaryTableName)
    {
        $this->primaryTableName = $primaryTableName;

        return $this;
    }

    /**
     * @param mixed $primaryKey
     * @return DataProvider
     */
    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;

        return $this;
    }

    /**
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    public function getTables()
    {
        $this->tables = $this->tablesProvider->setRootTableName($this->primaryTableName)->getTables();

        foreach ($this->tables as $table) {
            foreach ($this->getTableData($table) as $tableData) {
                $dataRow = new DataRow();

                foreach ($table->getColumns() as $column) {
                    $dataColumn = new DataColumn($column->getName());
                    $dataColumn->setValue($tableData[$column->getName()]);
                    $dataRow->addDataColumn($dataColumn);
                }

                $table->addDataRow($dataRow);
            }
        }

        return $this->tables;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return array
     */
    abstract protected function getTableData(Table $table);
}
