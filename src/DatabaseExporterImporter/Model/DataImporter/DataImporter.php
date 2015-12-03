<?php
namespace DatabaseExporterImporter\Model\DataImporter;

use DatabaseExporterImporter\Entity\Table;
use DatabaseExporterImporter\Model\Column\AutoIncrementTableColumnFinder;
use DatabaseExporterImporter\Model\DataParser\DataParser;
use DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementChangeEvent;
use DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementObserver;

/**
 * Class DataImporter
 * @package DatabaseExporterImporter\Model\DataImporter
 */
abstract class DataImporter
{
    /**
     * @var \DatabaseExporterImporter\Model\Column\AutoIncrementTableColumnFinder
     */
    protected $autoIncrementFinder;
    /**
     * @var \DatabaseExporterImporter\Model\DataParser\DataParser
     */
    protected $dataParser;
    /**
     * @var \DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementObserver
     */
    protected $observer;

    /**
     * @throws \Exception
     */
    public function import()
    {
        $tables = $this->dataParser->getTables();
        $this->observer->setTables($tables);
        $autoIncrementEvent = new AutoIncrementChangeEvent();

        foreach ($tables as $table) {
            $autoIncrementEvent->setColumn($this->autoIncrementFinder->getAutoIncrementColumn($table));

            foreach ($table->getDataRows() as $dataRow) {
                $dataRowValues = [];

                foreach ($dataRow->getDataColumns() as $dataColumn) {
                    if (null !== $autoIncrementEvent->getColumn() &&
                        $autoIncrementEvent->getColumn()->getName() === $dataColumn->getName()
                    ) {
                        $autoIncrementEvent->setOldValue($dataColumn->getValue());

                        continue;
                    }

                    $dataRowValues[] = $dataColumn;
                }

                $lastInsertId = $this->insertRecord($table, $dataRowValues);

                if (null !== $autoIncrementEvent->getColumn()) {
                    $this->observer->notify($autoIncrementEvent->setNewValue($lastInsertId));
                }
            }
        }
    }

    /**
     * @param \DatabaseExporterImporter\Entity\Table        $table
     * @param \DatabaseExporterImporter\Entity\DataColumn[] $values
     * @return int
     */
    abstract protected function insertRecord(Table $table, array $values);

    /**
     * @param \DatabaseExporterImporter\Model\DataParser\DataParser $dataParser
     * @return $this
     */
    public function setDataParser(DataParser $dataParser)
    {
        $this->dataParser = $dataParser;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Model\Observer\Import\AutoIncrementObserver\AutoIncrementObserver $observer
     * @return $this
     */
    public function setObserver(AutoIncrementObserver $observer)
    {
        $this->observer = $observer;

        return $this;
    }

    /**
     * @param \DatabaseExporterImporter\Model\Column\AutoIncrementTableColumnFinder $autoIncrementFinder
     * @return $this
     */
    public function setAutoIncrementFinder(AutoIncrementTableColumnFinder $autoIncrementFinder)
    {
        $this->autoIncrementFinder = $autoIncrementFinder;

        return $this;
    }
}
