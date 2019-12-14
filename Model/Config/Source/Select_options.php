<?php 

namespace FME\Contactus\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Select_options implements ArrayInterface
{   
    public function toOptionArray()  {
      return [

            ['value' => 'null', 'label' => __('--Required--')],
            ['value' => 'yes', 'label' => __('Yes')],
            ['value' => 'no', 'label' => __('No')],
           
        ];
    }
}
