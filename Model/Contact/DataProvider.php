<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace FME\Contactus\Model\Contact;

use FME\Contactus\Model\ResourceModel\Contact\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var \Magento\Cms\Model\ResourceModel\Block\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $blockCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $blockCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData() {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Magento\Cms\Model\Block $block */
        foreach ($items as $block) {
            $this->loadedData[$block->getId()] = $block->getData();
        }
        
        $data = $this->dataPersistor->get('fme_contactus');

        if (!empty($data)) {
            $block = $this->collection->getNewEmptyItem();
            $block->setData($data);
            $this->loadedData[$block->getId()] = $block->getData();
             
            $this->dataPersistor->clear('fme_contactus');
        }
        return $this->loadedData;
    }
}
