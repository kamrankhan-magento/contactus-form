<?php
namespace  FME\Contactus\Block\Adminhtml;

use Magento\Framework\View\Element\Template;

//use Magento\Framework\ObjectManagerInterface;

class Replyblock extends Template
{ 
    protected $_storeInfo;
    protected $_submitFactory; 
    public function __construct(
      Template\Context $context,
      \FME\Contactus\Model\SubmitFactory $submitFactory,        
      array $data = []       
      ){
      parent::__construct($context, $data);
      $this->_submitFactory = $submitFactory;
      $collection = $this->_submitFactory->create()->getCollection();
      $this->setCollection($collection);
    }
    public function getOptionValues(){
       return $this->_optionsFactory->create()->getCollection();
     }
     protected function _prepareLayout(){
      parent::_prepareLayout();
      return $this;
    }
    public function getFormAction(){
      return $this->getUrl('contactus/front/save', ['_secure' => true]);
    }
}
