<?php

namespace FME\Contactus\Model\ResourceModel\Submit;

use \FME\Contactus\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
    
    protected function _construct()
    {
        $this->_init('FME\Contactus\Model\Submit', 'FME\Contactus\Model\ResourceModel\Submit');
        $this->_map['fields']['id'] ='main_table.id';
    }
}
