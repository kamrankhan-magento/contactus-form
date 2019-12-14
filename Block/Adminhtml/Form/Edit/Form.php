<?php

namespace FME\Contactus\Block\Adminhtml\Form\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic 
{
  protected $_coreRegistry;
  protected $_gridFactory; 
  protected $_required_options; 
  protected $_optionsFactory;
  protected $_scopeConfig;

  public function __construct(
    \Magento\Backend\Block\Template\Context $context, 
    \Magento\Framework\Registry $registry, 
    \Magento\Framework\Data\FormFactory $formFactory, 
    \FME\Contactus\Model\FormFactory $gridFactory,
    \FME\Contactus\Model\OptionsFactory $optionsFactory,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, 
    array $data = []
    ) {
    parent::__construct($context, $registry, $formFactory, $data);
    $this->_coreRegistry = $registry;
    $this->_gridFactory = $gridFactory;
    $this->_optionsFactory = $optionsFactory;
    $this->_scopeConfig = $scopeConfig;   
  }     
  protected function _prepareForm() 
  {
    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
    $file_extensions = $this->_scopeConfig->getValue("contactus/file_extensions/file_ext", $storeScope);
    $fileArray = explode(',', $file_extensions);
    $formModel = $this->_gridFactory->create()->getCollection();
    $optionModel = $this->_optionsFactory->create()->getCollection();
    $form = $this->_formFactory->create(
      ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
      );
    $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Edit Field Information')]);
    $id = $this->getRequest()->getParam('field_id');
    if ($id) {
      $fieldset->addField('field_id', 'hidden', ['name' => 'field_id']);
    }
    foreach ($formModel->getData() as $_gridrecord):
      $_id = $_gridrecord['field_id'];
    $_label = $_gridrecord['label'];                        
    $_placeholder = $_gridrecord['placeholder'];
    $_value = $_gridrecord['value'];
    $_required = $_gridrecord['required'];
    $_type = $_gridrecord['type']; 
    $_sortorder = $_gridrecord['sortorder'];
    $_validation = $_gridrecord['validation']; 
    $_file_size = $_gridrecord['file_size'];
    $_status = $_gridrecord['status'];
    $_stat = [];
    $optionsArray = [];
    $multiArray = [];
    
    foreach ($optionModel->getData() as $option):         
      $opt_val = $option['value']; 
    $opt_id = $option['field_id'];

    if($opt_id==$_id):
      $multiArray[] = ['label'=>$opt_val, 'value'=>$opt_val];   
    $optionsArray[] = $opt_val;                      
    endif;  

    endforeach; 
    if($id==$_id):
      if($_status==0):
        $_stat = __('Disable');
      endif;
      if($_status==1):
        $_stat = __('Enable');
      endif;
      if($_type=="input_field"):
        $fieldset->addField('id','text',[
          			'label' => __('Field ID'),
          			'name'=> 'field_id',
          			'value' => $_id,
          			'readonly' => true
          ]);
      $fieldset->addField('field_type','text',[
        			'label' => __('Field Type'),
        			'name'=> 'field_type',
        			'value' => 'Text Box',
        			'disabled'=> true
        ]);
      $fieldset->addField('label','text',[
        			'label' => __('Label'),
        			'name'=> 'label',
        			'value' => $_label
        ]);
      $fieldset->addField('placeholder','text',[
        			'label' => __('Placeholder'),
        			'name'=> 'placeholder',
        			'value' => $_placeholder
        ]);
      $fieldset->addField('required', 'select', [
        			'label' => __('Required'), 
        			'name'=> 'required',        
        			'values' => ['yes'=>'Yes', 'no'=>'No'],
        			'value'=> $_required
        ]); 
      $fieldset->addField('validation', 'select', [
        			'label' => __('Validation'),
       				'name'=> 'validation',          
        			'values' => ['text'=>'text','email'=>'email','number'=>'number'],
        			'value' => $_validation
        ]); 
      $fieldset->addField('sortorder','text',[
                    'label' => __('Sortorder'),
                    'name'=> 'sortorder',
                    'value' => $_sortorder
        ]);
      $fieldset->addField('status', 'select', [
                    'label' => __('Status'),  
					'name'=> 'status',   
        			'values' => ['1' => __('Enable'), '0' => __('Disable')],
        			'value'=> $_status
        ]);
      endif;
      if($_type=="textarea_field"):
        $fieldset->addField('id','text',[
          			'label' => __('Field ID'),
          			'name'=> 'field_id',
          			'value' => $_id,
          			'readonly' => true
          ]);
      $fieldset->addField('field_type','text',[
        			'label' => __('Field Type'),
        			'name'=> 'field_type',
        			'value' => 'TextArea',
        			'disabled'=>true
        ]);
      $fieldset->addField('label','text',[
        			'label' => __('Label'),
        			'name' => 'label',
        			'value' => $_label
        ]);
      $fieldset->addField('placeholder','text',[
        			'label' => __('Placeholder'),
        			'name' => 'placeholder',
        			'value' => $_placeholder
        ]);
      $fieldset->addField('required', 'select', [
        			'label' => __('Required'),  
        			'name' => 'required',       
        			'values' => ['yes'=>'Yes', 'no'=>'No'],
        			'value'=> $_required
        ]);
      $fieldset->addField('sortorder','text',[
        			'label' => __('Sortorder'),
        			'name' => 'sortorder',
        			'value' => $_sortorder
        ]);
      $fieldset->addField('status', 'select', [
        			'label' => __('Status'), 
        			'name' => 'status',  
        			'values' => ['1' => __('Enable'), '0' => __('Disable')],
        			'value'=> $_status
        ]);
      endif;
      if($_type=="select_field"):
        $fieldset->addField('id','text',[
          			'label' => __('Field ID'),
          			'name'=> 'id',
          			'value' => $_id,
          			'readonly' => true
          ]);
      $fieldset->addField('field_type','text',[
        			'label' => __('Field Type'),
        			'name'=> 'field_type',
        			'value' => 'Dropdown',
        			'disabled' => true
        ]);
      $fieldset->addField('label','text',[
        			'label' => __('Label'),
        			'name' => 'label',
        			'value' => $_label
        ]);
       $fieldset->addField('value', 'multiselect', [
	      			'label' => __('Values'), 
          			'name' => 'value',
          			'after_element_html' => $this->_getValues(),  
          			'values' =>  $multiArray 
       ]);
       $fieldset->addField('required', 'select', [
	      			'label' => __('Required'),  
          			'name' => 'required',       
          			'values' => ['yes'=>'Yes', 'no'=>'No'],
          			'value' => $_required
       ]); 
      $fieldset->addField('sortorder','text',[
          			'label' => __('Sortorder'),
          			'name' => 'sortorder',
          			'value' => $_sortorder
       ]);
       $fieldset->addField('status', 'select', [
          			'label' => __('Status'),   
          			'name' => 'status',     
          			'values' => ['1' => __('Enable'), '0' => __('Disable')],
          			'value' => $_status
       ]); 
    endif;
    if($_type=="multiselect_field"):
      $fieldset->addField('id','text',[
        			'label' => __('Field ID'),
        			'name'=> 'id',
        			'value' => $_id,
        			'readonly' => true
        ]);
    $fieldset->addField('field_type','text',[
      			'label' => __('Field Type'),
      			'name'=> 'field_type',
      			'value' => 'Multi-Select',
      			'disabled' => true
      ]);
    $fieldset->addField('label','text',[
      			'label' => __('Label'),
      			'name' => 'label',
      			'value' => $_label
      ]);
    $fieldset->addField('value', 'multiselect', [
      			'label' => __('Values'), 
      			'name' => 'value',
      			'after_element_html' => $this->_getValues(),  
      			'values' =>  $multiArray
      ]);
    $fieldset->addField('required', 'select', [
      			'label' => __('Required'),
      			'name' => 'required',         
      			'values' => ['yes'=>'Yes', 'no'=>'No'],
      			'value'=> $_required
      ]); 
    $fieldset->addField('sortorder','text',[
      			'label' => __('Sortorder'),
      			'name' => 'sortorder',
      			'value' => $_sortorder
      ]);
    $fieldset->addField('status', 'select', [
      			'label' => __('Status'),   
      			'name' => 'status',     
      			'values' => ['1' => __('Enable'), '0' => __('Disable')],
      			'value'=> $_status
      ]);
    endif;
    if($_type=="radio_field"):
      $fieldset->addField('id','text',[
        'label' => __('Field ID'),
        'name'=> 'id',
        'value' => $_id,
        'readonly' => true
        ]);
    $fieldset->addField('field_type','text',[
      'label' => __('Field Type'),
      'name'=> 'field_type',
      'value' => 'Radio Button',
      'disabled'=>true
      ]);
    $fieldset->addField('label','text',[
      'label' => __('Label'),
      'name' => 'label',
      'value' => $_label
      ]);
    $fieldset->addField('value', 'multiselect', [
      'label' => __('Values'), 
      'name' => 'value',
      'after_element_html' => $this->_getValues(),  
      'values' =>  $multiArray 
       ]);
    $fieldset->addField('required', 'select', [
      'label' => __('Required'),  
      'name' => 'required',      
      'values' => ['yes'=>'Yes', 'no'=>'No'],
      'value'=> $_required
      ]); 
    $fieldset->addField('sortorder','text',[
      'label' => __('Sortorder'),
      'name' => 'sortorder',
      'value' => $_sortorder
      ]);
    $fieldset->addField('status', 'select', [
      'label' => __('Status'), 
      'name' => 'status',       
      'values' => ['1' => __('Enable'), '0' => __('Disable')],
      'value'=> $_status
      ]);
    endif;
    if($_type=="checkbox_field"):
      $fieldset->addField('id','text',[
        'label' => __('Field ID'),
        'name'=> 'id',
        'value' => $_id,
        'readonly' => true
        ]);
    $fieldset->addField('field_type','text',[
      'label' => __('Field Type'),
      'name'=> 'field_type',
      'value' => 'CheckBox',
      'disabled'=>true
      ]);
    $fieldset->addField('label','text',[
      'label' => __('Label'),
      'name' => 'label',
      'value' => $_label
      ]);
    $fieldset->addField('value', 'multiselect', [
      'label' => __('Values'), 
      'name' => 'value',
      'after_element_html' => $this->_getValues(),  
      'values' =>  $multiArray 
      ]);
    $fieldset->addField('required', 'select', [
      'label' => __('Required'),  
      'name' => 'required',      
      'values' => ['yes'=>'Yes', 'no'=>'No'],
      'value' => $_required
      ]); 
    $fieldset->addField('sortorder','text',[
      'label' => __('Sortorder'),
      'name' => 'sortorder',
      'value' => $_sortorder
      ]);
    $fieldset->addField('status', 'select', [
      'label' => __('Status'), 
      'name' => 'status',       
      'values' => ['1' => __('Enable'), '0' => __('Disable')],
      'value' => $_status
      ]);
    endif;
    if($_type=="upload_field"):
      $fieldset->addField('id','text',[
        'label' => __('Field ID'),
        'name'=> 'field_id',
        'value' => $_id,
        'readonly' => true
        ]);
    $fieldset->addField('field_type','text',[
      'label' => __('Field Type'),
      'name'=> 'field_type',
      'value' => 'File',
      'disabled'=>true
      ]);
    $fieldset->addField('label','text',[
      'label' => __('Label'),
      'name' => 'label',
      'value' => $_label
      ]);
    $fieldset->addField('file_size','text',[
      'label' => __('File Size (MB)'),
      'name' => 'file_size', 
      'value' => $_file_size
      ]);
    $fieldset->addField('file_type', 'select', [
      'label' => __('File Type'),  
      'name' => 'file_type',      
      'values' => $fileArray
      ]); 
    $fieldset->addField('required', 'select', [
      'label' => __('Required'),  
      'name' => 'required',      
      'values' => ['yes'=>'Yes', 'no'=>'No'],
      'value' => $_required
      ]); 
    $fieldset->addField('sortorder','text',[
      'label' => __('Sortorder'),
      'name' => 'sortorder',
      'value' => $_sortorder
      ]);
    $fieldset->addField('status', 'select', [
      'label' => __('Status'), 
      'name' => 'status',       
      'values' => ['1' => __('Enable'), '0' => __('Disable')],
      'value'=> $_status
      ]);
    endif;
    if($_type=="date_field"):
      $fieldset->addField('id','text',[
        'label' => __('Field ID'),
        'name'=> 'field_id',
        'value' => $_id,
        'readonly' => true
        ]);
    $fieldset->addField('field_type','text',[
      'label' => __('Field Type'),
      'name'=> 'field_type',
      'value' => 'File',
      'disabled'=>true
      ]);
    $fieldset->addField('label','text',[
      'label' => __('Label'),
      'name' => 'label',
      'value' => $_label
      ]);
    $fieldset->addField('required', 'select', [
      'label' => __('Required'),  
      'name' => 'required',      
      'options' => ['yes'=>'Yes', 'no'=>'No'],
      'value'=> $_required
      ]); 
    $fieldset->addField('sortorder','text',[
      'label' => __('Sortorder'),
      'name' => 'sortorder',
      'value' => $_sortorder
      ]);
    $fieldset->addField('status', 'select', [
      'label' => __('Status'), 
      'name' => 'status',       
      'values' => ['1' => __('Enable'), '0' => __('Disable')],
      'value'=> $_status
      ]);
    endif;
    endif; 
    endforeach;
    $form->setUseContainer(true);
    $this->setForm($form);
    return parent::_prepareForm();
  }
  protected function _getValues()
  {
      $html='<div><br><input class="input-text admin__control-text" type="text" id="val" size="14"/><p style="margin-top:15px;"><button type="button" href="javascript:void(0)" onclick="insertValue();">Add Value</button></p></div>';
      $html.='<div><p style="margin-top:20px;"><button type="button" href="javascript:void(0)" onclick="deleteValue();">Delete Selected Value</button></p></div>';
      $html.='<script>;
      function insertValue(){
        var select = document.getElementById("value"),
        txtVal = document.getElementById("val").value,
        newOption = document.createElement("option"),
        newOptionVal = document.createTextNode(txtVal);
        newOption.appendChild(newOptionVal);
        newOption.selected = true;
        select.insertBefore(newOption,select.firstChild);
      };
      function deleteValue(){
        var i;
        var selectbox = document.getElementById("value");
        for(i=selectbox.options.length-1;i>=0;i--){
          if(selectbox.options[i].selected)
            selectbox.remove(i);
        }
      };
      for (var i = 0; i < value.options.length; i++) { 
        value.options[i].selected = true; 
      } 
      </script>';
      return $html;
    }
  }
