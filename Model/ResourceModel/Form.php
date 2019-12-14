<?php

namespace FME\Contactus\Model\ResourceModel;

class Form extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{   
	protected function _construct() 
    {
        $this->_init('fme_contactus_custom_form', 'field_id');
    }     
}
