<?php 

namespace FME\Contactus\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class FileType implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()  {
        
       return [
               ['value' => 0,    'label' => '--file type--'],
               ['value' => 'jpg',    'label' => 'JPG'],        
               ['value' => 'png',   'label' => 'PNG'],    
               ['value' => 'docx',  'label' => 'DOCX'],        
               ['value' => 'pdf', 'label' => 'PDF'],
           ];      
    }
}
