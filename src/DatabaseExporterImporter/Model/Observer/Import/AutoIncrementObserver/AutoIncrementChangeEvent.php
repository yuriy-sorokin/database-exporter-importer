<?php
namespace DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver;

use DatabaseExporterImporter\Entity\Column;

/**
 * Class AutoIncrementChangeEvent
 * @package DatabaseExporterImporter\Model\AutoIncrementObserver\Import\AutoIncrementObserver
 */
class AutoIncrementChangeEvent
{
    /**
     * @var \DatabaseExporterImporter\Entity\Column
     */
    private $column;
    /**
     * @var mixed
     */
    private $newValue;
    /**
     * @var mixed
     */
    private $oldValue;

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Column $column
     * @return AutoIncrementChangeEvent
     */
    public function setColumn(Column $column = null)
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOldValue()
    {
        return $this->oldValue;
    }

    /**
     * @param mixed $oldValue
     * @return AutoIncrementChangeEvent
     */
    public function setOldValue($oldValue)
    {
        $this->oldValue = $oldValue;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewValue()
    {
        return $this->newValue;
    }

    /**
     * @param mixed $newValue
     * @return AutoIncrementChangeEvent
     */
    public function setNewValue($newValue)
    {
        $this->newValue = $newValue;

        return $this;
    }
}
