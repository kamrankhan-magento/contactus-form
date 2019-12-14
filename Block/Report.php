<?php
namespace FME\Contactus\Block;

use Magento\Framework\View\Element\Template;

class Report extends Template
{
    protected $_coreRegistry;
    protected $_contactFactory; 
    protected $_required_options; 
    protected $_submitFactory;
    protected $_gridFactory;    
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,  
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory, 
        \FME\Contactus\Model\ContactFactory $contactFactory,
        \FME\Contactus\Model\SubmitFactory $submitFactory, 
        \FME\Contactus\Model\FormFactory $gridFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_contactFactory = $contactFactory;
        $this->_submitFactory = $submitFactory;
        $this->_gridFactory = $gridFactory;          
    }     
    protected function _prepareLayout(){
       $contactModel = $this->_contactFactory->create()->getCollection();
       $submitModel = $this->_submitFactory->create()->getCollection();
        $param = $this->getRequest()->getParam('contact_id');   
        $count = 0;  
        return parent::_prepareLayout();
    }
}
