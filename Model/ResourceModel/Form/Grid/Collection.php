<?php

namespace FME\Contactus\Model\ResourceModel\Form\Grid;

use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Search\AggregationInterface;
use FME\Contactus\Model\ResourceModel\Form\Collection as QuoteCollection;

class Collection extends QuoteCollection implements SearchResultInterface
{  
    protected $aggregations;

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        $mainTable,
        $eventPrefix,
        $eventObject,
        $resourceModel,
        $model = 'Magento\Framework\View\Element\UiComponent\DataProvider\Document',
        $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->_eventPrefix = $eventPrefix;
        $this->_eventObject = $eventObject;
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }
  
    public function getAggregations() {
        return $this->aggregations;
    }
  
    public function setAggregations($aggregations) {
        $this->aggregations = $aggregations;
    }
     
    public function getSearchCriteria() {
        return null;
    }
  
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null) {
        return $this;
    }
 
    public function getTotalCount() {
        return $this->getSize();
    }
  
    public function setTotalCount($totalCount) {
        return $this;
    }

    public function setItems(array $items = null) {
        return $this;
    }
}
