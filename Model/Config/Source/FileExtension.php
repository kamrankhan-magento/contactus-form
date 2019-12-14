<?php 
namespace FME\Contactus\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class FileExtension implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray() {
        
       return [
               ['value' => 'PNG'   ,'label' => 'PNG'],        
               ['value' => 'JPG'   ,'label' => 'JPG'],    
               ['value' => 'DOCX'  ,'label' => 'DOCX'],        
               ['value' => 'TXT'   ,'label' => 'TXT'],
               ['value' => 'PPT'   ,'label' => 'PPT'],
               ['value' => 'ZIP'   ,'label' => 'ZIP'],

           ];      
    }
}
