<?php
namespace FME\Contactus\Model;

class Form extends \Magento\Framework\Model\AbstractModel
{ 
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const CACHE_TAG = 'contactus_form';
    protected $_cacheTag = 'contactus_form';
    protected $_eventPrefix = 'contactus_form';
  
    protected function _construct() {
        $this->_init('FME\Contactus\Model\ResourceModel\Form');
    }

    public function loadfield($field_id)  {
           
        $ansCollection = $this->getCollection()->addFieldToFilter('field_id', $field_id)
                                                ->addFieldToFilter('status', 1);
        return $ansCollection->getData();
    }

    public function getIdentities(){
        return [self::CACHE_TAG . '_' . $this->getId(), self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getAvailableStatuses(){
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

}
