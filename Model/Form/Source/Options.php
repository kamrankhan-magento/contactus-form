<?php
namespace FME\Contactus\Model\Form\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{  
    public function toOptionArray(){   
        $availableOptions = ['Yes' => 'yes', 'No' => 'no'];
        
        $options = [];
        foreach ($availableOptions as $key => $label) {
            $options[] = [
                'label' => $label,
                'value' => $key,
            ];
        }
        return $options;
    }
}
