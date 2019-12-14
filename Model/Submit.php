<?php
namespace FME\Contactus\Model;

class Submit extends \Magento\Framework\Model\AbstractModel
{ 
    protected function _construct() {
        $this->_init('FME\Contactus\Model\ResourceModel\Submit');
    }
}
