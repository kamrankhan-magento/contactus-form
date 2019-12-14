<?php 

namespace FME\Contactus\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Options implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray() {     
       return [
               ['value' => 0,'label' => '--field type--'],        
               ['value' => 'text','label' => 'text'],    
               ['value' => 'email','label' => 'email'],        
               ['value' => 'number','label' => 'number'],
           ];    
    }
}
