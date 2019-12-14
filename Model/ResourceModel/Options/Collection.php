<?php

namespace FME\Contactus\Model\ResourceModel\Options;

use \FME\Contactus\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'field_id';

    protected function _construct()
    {
        $this->_init('FME\Contactus\Model\Options', 'FME\Contactus\Model\ResourceModel\Options');
        $this->_map['fields']['field_id'] ='main_table.field_id';
    }
}
