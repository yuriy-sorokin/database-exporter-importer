<?php
namespace DatabaseExporterImporter\Entity;

/**
 * Class DataColumn
 * @package DatabaseExporterImporter\Entity
 */
class DataColumn
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var bool
     */
    private $originalValue = true;
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return boolean
     */
    public function isOriginalValue()
    {
        return $this->originalValue;
    }

    /**
     * @param boolean $originalValue
     * @return DataColumn
     */
    public function setOriginalValue($originalValue)
    {
        $this->originalValue = $originalValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return DataColumn
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
