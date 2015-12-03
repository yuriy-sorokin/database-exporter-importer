<?php
namespace DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver;

use DatabaseExporterImporter\Model\Column\ForeignKeyColumnsFinder;

/**
 * Class AutoIncrementObserver
 * @package DatabaseExporterImporter\Model\AutoIncrementObserver\Import\AutoIncrementObserver
 */
class AutoIncrementObserver
{
    /**
     * @var \DatabaseExporterImporter\Model\Column\ForeignKeyColumnsFinder
     */
    private $foreignKeysFinder;
    /**
     * @var \DatabaseExporterImporter\Entity\Table[]
     */
    private $tables;

    /**
     * @param \DatabaseExporterImporter\Model\Column\ForeignKeyColumnsFinder $foreignKeysFinder
     */
    public function __construct(ForeignKeyColumnsFinder $foreignKeysFinder)
    {
        $this->foreignKeysFinder = $foreignKeysFinder;
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table[] $tables
     * @return AutoIncrementObserver
     */
    public function setTables(array $tables)
    {
        $this->tables = $tables;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementChangeEvent $event
     * @throws \RuntimeException
     */
    public function notify(AutoIncrementChangeEvent $event)
    {
        $dependentColumns = $this->foreignKeysFinder
            ->setTables($this->tables)
            ->setParentColumn($event->getColumn())
            ->getDependentColumns();

        foreach ($dependentColumns as $dependentColumn) {
            foreach ($this->tables as $table) {
                if ($table->getName() === $dependentColumn->getTable()->getName()) {
                    foreach ($table->getDataRows() as $dataRow) {
                        foreach ($dataRow->getDataColumns() as $dataColumn) {
                            if (true === $dataColumn->isOriginalValue() &&
                                $dataColumn->getName() === $dependentColumn->getName() &&
                                $dataColumn->getValue() === $event->getOldValue()
                            ) {
                                $dataColumn
                                    ->setOriginalValue(false)
                                    ->setValue($event->getNewValue());
                            }
                        }
                    }
                }
            }
        }
    }
}
