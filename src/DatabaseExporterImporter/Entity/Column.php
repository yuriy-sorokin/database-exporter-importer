<?php
namespace DatabaseExporterImporter\Entity;

/**
 * Class Column
 * @package DatabaseExporterImporter\Entity
 */
class Column
{
    /**
     * @var string
     */
    private $name;
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
     * @return Column
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
