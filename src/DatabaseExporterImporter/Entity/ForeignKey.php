<?php
namespace DatabaseExporterImporter\Entity;

/**
 * Class ForeignKey
 * @package DatabaseExporterImporter\Entity
 */
class ForeignKey
{
    /**
     * @var string
     */
    private $columnName;
    /**
     * @var string
     */
    private $tableName;

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return ForeignKey
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     * @return ForeignKey
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;

        return $this;
    }
}
