<?php
namespace DatabaseExporterImporter\Entity;

/**
 * Class Column
 * @package DatabaseExporterImporter\Entity
 */
class Column
{
    /**
     * @var bool
     */
    private $autoIncrement = false;
    /**
     * @var \DatabaseExporterImporter\Entity\ForeignKey
     */
    private $foreignKey;
    /**
     * @var string
     */
    private $name;
    /**
     * @var \DatabaseExporterImporter\Entity\Table
     */
    private $table;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return $this
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param boolean $autoIncrement
     * @return Column
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;

        return $this;
    }

    /**
     * @return \DatabaseExporterImporter\Entity\ForeignKey
     */
    public function getForeignKey()
    {
        return $this->foreignKey;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\ForeignKey $foreignKey
     * @return $this
     */
    public function setForeignKey(ForeignKey $foreignKey)
    {
        $this->foreignKey = $foreignKey;

        return $this;
    }

    /**
     * @return $this
     */
    public function removeForeignKey()
    {
        $this->foreignKey = null;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
