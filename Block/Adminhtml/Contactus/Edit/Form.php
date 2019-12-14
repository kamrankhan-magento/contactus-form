<?php

namespace FME\Contactus\Block\Adminhtml\Contactus\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
  protected  $_coreRegistry;
  protected  $_contactFactory; 
  protected  $_required_options; 
  protected  $_submitFactory;
  protected  $_gridFactory; 

  public function __construct(
    \Magento\Backend\Block\Template\Context $context,  
    \Magento\Framework\Registry $registry,
    \Magento\Framework\Data\FormFactory $formFactory, 
    \FME\Contactus\Model\ContactFactory $contactFactory,
    \FME\Contactus\Model\SubmitFactory $submitFactory, 
    \FME\Contactus\Model\FormFactory $gridFactory,
      array $data = []
    ) {  
        parent::__construct($context,$registry, $formFactory, $data);
        $this->_contactFactory = $contactFactory;
        $this->_submitFactory = $submitFactory;
        $this->_coreRegistry = $registry;
        $this->_gridFactory = $gridFactory;
      }

      protected function _prepareForm()
      {
        $contactModel = $this->_contactFactory->create()->getCollection();
        $submitModel = $this->_submitFactory->create()->getCollection();
        $form = $this->_formFactory->create(
          ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
          );
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Report')]);
        $param = $this->getRequest()->getParam('contact_id');

        if ($param)
        {
            $fieldset->addField('contact_id', 'hidden', ['name' => 'contact_id']);
        }

        $count = 0;

        foreach ($contactModel->getData() as $_gridrecord):
            $_id = $_gridrecord['contact_id'];
            foreach ($submitModel->getData() as $option): 
              $opt_id = $option['contact_id'];
              $opt_label = $option['field_label'];
              $opt_value = $option['field_value'];
              $type = is_numeric($opt_label);

              if($type==false):
                $opt_label = ucfirst($opt_label);
                $opt_label = str_replace( '_', ' ', $opt_label); 

                    if($_id==$param):
                      if($_id==$opt_id):
                        $fieldset->addField($opt_label,'text',[
                          'label'=> $opt_label,
                          'name'=> $opt_label,
                          'value' => $opt_value,
                          'disabled' => true
                       ]);
                    endif;
                  endif;  
              endif;
                  
                if($type==true):                  
                     $gridModel = $this->_gridFactory->create()->load($opt_label,'field_id');
                     $field_label = $gridModel->getLabel();
                     $field_label = ucfirst($field_label);
                     $field_label = str_replace( '_', ' ', $field_label);

                   if($_id==$param):
                       if($_id==$opt_id):

                          $fieldset->addField($field_label,'text',[
                          'label'=> $field_label,
                          'name'=> $field_label,
                          'value' => $opt_value,
                          'disabled' => true
                       ]);

                     endif;
                  endif;                  
                endif;                     
            endforeach; 
        endforeach;     
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _getValues()
    {
      $html='<div><br><input type="text" id="val" size="14"/><a href="javascript:void(0)" onclick="insertValue();">Add Value</a></div><br>';
      $html.='<div><a href="javascript:void(0)" onclick="deleteValue(value);">Delete Selected Value</a></div>';
      $html.='<script>;
            
      function insertValue()
      {
        var select = document.getElementById("value"),
        txtVal = document.getElementById("val").value,
        newOption = document.createElement("option"),
        newOptionVal = document.createTextNode(txtVal);       
  	    newOption.appendChild(newOptionVal);
        newOption.selected = true;
  	    select.insertBefore(newOption,select.firstChild);
      };
            
      function deleteValue(selectbox)
      {
        var i;
	      for(i=selectbox.options.length-1;i>=0;i--)
	      {
          if(selectbox.options[i].selected)
			    selectbox.remove(i);
		    }
      };

      for (var i = 0; i < value.options.length; i++)
      { 
        value.options[i].selected = true; 
      }             
      </script>';
      return $html;
    }
}
