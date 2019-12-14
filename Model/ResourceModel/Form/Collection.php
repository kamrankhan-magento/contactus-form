<?php

namespace FME\Contactus\Model\ResourceModel\Form;

use \FME\Contactus\Model\ResourceModel\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'field_id';

    protected function _construct()  {
        $this->_init('FME\Contactus\Model\Form', 'FME\Contactus\Model\ResourceModel\Form');
    }
}
