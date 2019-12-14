<?php
namespace FME\Contactus\Block\Adminhtml\Form;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    protected $_coreRegistry = null;
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context, 
        \Magento\Framework\Registry $registry, 
        array $data = []
    ){
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
    protected function _construct() {
        $this->_objectId = 'field_id';
        $this->_blockGroup = 'FME_Contactus';
        $this->_controller = 'adminhtml_form';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save and Continue Edit'));
        $this->buttonList->update('delete', 'label', __('Delete'));
    }
    public function getHeaderText(){
        return __('Edit Field Information');    
    }
    protected function _getSaveAndContinueUrl(){
        return $this->getUrl('*/*/save', ['_current' => true, 'back' => 'edit']);
    }
    protected function _prepareLayout(){
        $this->_formScripts[] = "
        function toggleEditor() {
            if (tinyMCE.getInstanceById('error_msg') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'error_msg');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'error_msg');
                }
            };
        ";
        return parent::_prepareLayout();
    }
}
