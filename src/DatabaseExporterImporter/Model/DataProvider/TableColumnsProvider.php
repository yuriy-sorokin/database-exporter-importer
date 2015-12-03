<?php
namespace DatabaseExporterImporter\Model\DataProvider;

use DatabaseExporterImporter\Entity\Table;

/**
 * Class TableColumnsProvider
 * @package DatabaseExporterImporter\Model\DataProvider
 */
abstract class TableColumnsProvider
{
    /**
     * @var \DatabaseExporterImporter\Entity\Column[]
     */
    protected $columns;
    /**
     * @var \DatabaseExporterImporter\Entity\Table
     */
    protected $table;

    /**
     * @param \DatabaseExporterImporter\Entity\Table $table
     * @return TableColumnsProvider
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @throws \RuntimeException
     * @return \DatabaseExporterImporter\Entity\Column[]
     */
    public function getTableColumns()
    {
        if (null === $this->table) {
            throw new \RuntimeException('Please, provide a target table.');
        }

        $this->columns = [];

        $this
            ->setColumns()
            ->setForeignKeys();

        return $this->columns;
    }

    /**
     * @return $this
     */
    abstract protected function setForeignKeys();

    /**
     * @return $this
     */
    abstract protected function setColumns();
}
