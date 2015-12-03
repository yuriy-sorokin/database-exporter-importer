<?php
namespace DatabaseExporterImporter\Entity;

/**
 * Class Table
 * @package DatabaseExporterImporter\Entity
 */
class Table
{
    /**
     * @var \DatabaseExporterImporter\Entity\Column[]
     */
    private $columns = [];
    /**
     * @var \DatabaseExporterImporter\Entity\DataRow[]
     */
    private $dataRows = [];
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\DataRow $dataRow
     * @return $this
     */
    public function addDataRow(DataRow $dataRow)
    {
        $this->dataRows[] = $dataRow;

        return $this;
    }

    /**
     * @return DataRow[]
     */
    public function getDataRows()
    {
        return $this->dataRows;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Column $column
     * @return $this
     */
    public function addColumn(Column $column)
    {
        $column->setTable($this);
        $this->columns[$column->getName()] = $column;

        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns()
    {
        return $this->columns;
    }
}
