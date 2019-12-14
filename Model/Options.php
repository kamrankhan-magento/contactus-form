<?php

namespace FME\Contactus\Model;

class Options extends \Magento\Framework\Model\AbstractModel
{ 
    protected function _construct() {
        $this->_init('FME\Contactus\Model\ResourceModel\Options');
    }
}
