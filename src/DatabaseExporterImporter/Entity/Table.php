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
