<?php

namespace FME\Contactus\Model\ResourceModel;

class Submit extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct() 
    {
        $this->_init('fme_contactus_form_save', 'id');
    }
}
