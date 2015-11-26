<?php
namespace DatabaseExporterImporter\Model\DataParser\JSON;

use DatabaseExporterImporter\Entity\Column;
use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\DataParser\DataParser;

/**
 * Class JsonDataParser
 * @package DatabaseExporterImporter\Model\DataParser\JSON
 */
class JsonDataParser extends DataParser
{
    /**
     * @var array
     */
    private $parsedData;

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

        foreach ($this->parsedData as $tableName => $tableColumns) {
            $table = new Table($tableName);

            if (false === is_array($tableColumns)) {
                throw new \InvalidArgumentException('The data structure is incorrect: table columns must be an array.');
            }

            foreach ($tableColumns as $columnName => $columnValue) {
                $column = new Column($columnName);
                $table->addColumn($column->setValue($columnValue));
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
