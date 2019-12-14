<?php 

namespace FME\Contactus\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Required implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray() {
      return [

            ['value' => 'null', 'label' => __('--Required--')],
            ['value' => 'yes', 'label' => __('Yes')],
            ['value' => 'no', 'label' => __('No')],
           
        ];
    }
}
