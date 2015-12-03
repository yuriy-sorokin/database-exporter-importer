<?php
namespace DatabaseExporterImporter\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataParser\DataParser;

/**
 * Class JsonDataParser
 * @package DatabaseExporterImporter\Model\DataParser\JSON
 */
class JsonDataParser extends DataParser
{
    /**
     * @var \DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator
     */
    private $columnsCreator;
    /**
     * @var \DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator
     */
    private $dataRowsCreator;
    /**
     * @var array
     */
    private $parsedData;

    /**
     * @param \DatabaseExporterImporter\Model\DataParser\JSON\JsonColumnsCreator $columnsCreator
     * @return $this
     */
    public function setColumnsCreator(JsonColumnsCreator $columnsCreator)
    {
        $this->columnsCreator = $columnsCreator;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Model\DataParser\JSON\JsonDataRowsCreator $dataRowsCreator
     * @return $this
     */
    public function setDataRowsCreator(JsonDataRowsCreator $dataRowsCreator)
    {
        $this->dataRowsCreator = $dataRowsCreator;

        return $this;
    }

    /**
     * @throws \InvalidArgumentException
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    public function getTables()
    {
        $this->parsedData = json_decode($this->data, true);

        return $this
            ->checkData()
            ->createTables();
    }

    /**
     * @throws \InvalidArgumentException
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    private function createTables()
    {
        $tables = [];

        foreach ($this->parsedData as $tableName => $tableData) {
            $table = new Table($tableName);

            if (false === is_array($tableData)) {
                throw new \InvalidArgumentException('The data structure is incorrect.');
            }

            if (false === array_key_exists('columns', $tableData)) {
                throw new \InvalidArgumentException("The data structure is incorrect: 'columns' key is absent.");
            }

            foreach ($this->columnsCreator->getColumns($tableData['columns']) as $column) {
                $table->addColumn($column);
            }

            foreach ($this->dataRowsCreator->getDataRows($tableData['data_rows']) as $dataRow) {
                $table->addDataRow($dataRow);
            }

            $tables[] = $table;
        }

        return $tables;
    }

    /**
     * @throws \InvalidArgumentException
     * @return $this
     */
    private function checkData()
    {
        if (false === is_array($this->parsedData)) {
            throw new \InvalidArgumentException('The data must represent a json string.');
        }

        return $this;
    }
}
