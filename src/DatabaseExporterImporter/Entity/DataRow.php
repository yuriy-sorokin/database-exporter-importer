<?php
namespace DatabaseExporterImporter\Entity;

/**
 * Class DataRow
 * @package DatabaseExporterImporter\Entity
 */
class DataRow
{
    /**
     * @var \DatabaseExporterImporter\Entity\DataColumn[]
     */
    private $dataColumns = [];

    /**
     * @param \DatabaseExporterImporter\Entity\DataColumn $dataColumn
     * @return $this
     */
    public function addDataColumn(DataColumn $dataColumn)
    {
        $this->dataColumns[] = $dataColumn;

        return $this;
    }

    /**
     * @return DataColumn[]
     */
    public function getDataColumns()
    {
        return $this->dataColumns;
    }
}
