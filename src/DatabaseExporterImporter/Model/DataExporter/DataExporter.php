<?php
namespace DatabaseExporterImporter\Model\DataExporter;

use DatabaseExporterImporter\Model\DataProvider\DataProvider;

/**
 * Class DataExporter
 * @package DatabaseExporterImporter\Model\DataExporter
 */
abstract class DataExporter
{
    /**
     * @var \DatabaseExporterImporter\Model\DataProvider\DataProvider
     */
    protected $dataProvider;

    /**
     * @param \DatabaseExporterImporter\Model\DataProvider\DataProvider $dataProvider
     * @return $this
     */
    public function setDataProvider(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getData();
}
