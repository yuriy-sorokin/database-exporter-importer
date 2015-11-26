<?php
namespace DatabaseExporterImporter\Model\DataImporter;

use DatabaseExporterImporter\Model\DataParser\DataParser;

/**
 * Class DataImporter
 * @package DatabaseExporterImporter\Model\DataImporter
 */
abstract class DataImporter
{
    /**
     * @var \DatabaseExporterImporter\Model\DataParser\DataParser
     */
    protected $dataParser;

    /**
     * @throws \Exception
     */
    abstract public function import();

    /**
     * @param \DatabaseExporterImporter\Model\DataParser\DataParser $dataParser
     * @return $this
     */
    public function setDataParser(DataParser $dataParser)
    {
        $this->dataParser = $dataParser;

        return $this;
    }
}
