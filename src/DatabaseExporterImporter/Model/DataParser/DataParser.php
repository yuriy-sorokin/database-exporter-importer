<?php
namespace DatabaseExporterImporter\Model\DataParser;

/**
 * Class DataParser
 * @package DatabaseExporterImporter\Model\DataParser
 */
abstract class DataParser
{
    /**
     * @var string
     */
    protected $data;

    /**
     * @param string $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @throws \Exception
     * @return \DatabaseExporterImporter\Entity\Table[]
     */
    abstract public function getTables();
}
