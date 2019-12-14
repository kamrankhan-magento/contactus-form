<?php
namespace FME\Contactus\Controller\Adminhtml\Form;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use FME\Contactus\Model\Form;
use FME\Contactus\Model\Options;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\Component\MassAction\Filter;
use FME\Contactus\Model\ResourceModel\Options\CollectionFactory;

class Save extends \Magento\Backend\App\Action
{
  protected $dataPersistor;
  protected $scopeConfig;
  protected $_transportBuilder;
  protected $_escaper;
  protected $inlineTranslation;
  protected $_dateFactory;
  protected $_registry;
  protected $_optionsFactory;
  protected $filter;
  protected $collectionFactory;

  public function __construct(
    Context $context,
    DataPersistorInterface $dataPersistor,
    \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
    \Magento\Framework\Escaper $escaper,
    \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory,
    \FME\Contactus\Helper\Data $myModuleHelper,
    \FME\Contactus\Model\OptionsFactory $optionFactory,
    Filter $filter,
    CollectionFactory $collectionFactory   
    ){
    $this->dataPersistor = $dataPersistor;
    $this->_transportBuilder = $transportBuilder;
    $this->scopeConfig = $scopeConfig;
    $this->_escaper = $escaper;
    $this->_dateFactory = $dateFactory;
    $this->_mymoduleHelper = $myModuleHelper;
    $this->inlineTranslation = $inlineTranslation;
    $this->_optionsFactory = $optionFactory;
    $this->filter = $filter;
    $this->collectionFactory = $collectionFactory;
    parent::__construct($context);
    }
    public function execute(){
      $resultRedirect = $this->resultRedirectFactory->create();
      $data = $this->getRequest()->getPostValue();
      if ($data) {  
        $id = $this->getRequest()->getParam('field_id');
        $option_id = $this->getRequest()->getParam('option_id');  
        $customModel = $this->_objectManager->create('FME\Contactus\Model\Form')->load($id);
        $optionModel = $this->_objectManager->create('FME\Contactus\Model\Options')->load($option_id);
        $opModel = $this->_objectManager->create('FME\Contactus\Model\Options');
        if(isset($data['value'])) {  
          $optionsData = $data;
          $this->Delete_Save($optionsData,$customModel,$optionModel,$opModel);                  
        }
        if($id){  
          $customModel->setData($data);
          $customModel->save();             
        }          
        if(!$customModel->getId() && $id) {
          $this->messageManager->addError(__('This Field no longer exists.'));
          return $resultRedirect->setPath('*/*/');
        }
        try{
          if(isset($data["answers_dynamic"])) {
            $textfields = $data["answers_dynamic"];
            $this->TextBoxField($textfields,$customModel);               
          }
          if(isset($data["answers_dynamic2"])) {
            $text_area_fields = $data["answers_dynamic2"]; 
            $this->TextAreaField($text_area_fields,$customModel);
          }
          if(isset($data["answers_dynamic3"])) {
            $dropdownds = $data["answers_dynamic3"];   
            $this->DropDownField($dropdownds,$customModel,$optionModel);                           
          }
          if(isset($data["answers_dynamic4"])) {
            $checkboxs = $data["answers_dynamic4"];
            $this->CheckBoxField($checkboxs,$customModel,$optionModel);  
          }
          if(isset($data["answers_dynamic5"])) {
            $uploadfields = $data["answers_dynamic5"];
            $this->UploadField($uploadfields,$customModel);                         
          }             
          if(isset($data["answers_dynamic6"])) {
            $radiofields = $data["answers_dynamic6"];
            $this->RadioBoxField($radiofields,$customModel,$optionModel);
          } 
          if(isset($data["answers_dynamic8"])) {
            $multiselect_fields = $data["answers_dynamic8"];
            $this->MultiselectField($multiselect_fields,$customModel,$optionModel);
          } 
          if(isset($data["answers_dynamic11"])) {
            $datefields = $data["answers_dynamic11"];
            $this->DateField($datefields,$customModel);
          }   
          $this->messageManager->addSuccess(__('Successfully saved the Field.'));
          $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
          return $resultRedirect->setPath('*/*/');
        }catch (\Exception $e){               
          $this->messageManager->addError($e->getMessage());
          $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData($data);
          return $resultRedirect->setPath('*/*/edit2', ['field_id' => $this->getRequest()->getParam('field_id')]);
        }
        $this->dataPersistor->set('fme_contactus_custom_form', $data);
        return $resultRedirect->setPath('*/*/edit2', ['field_id' => $this->getRequest()->getParam('field_id')]);
      }
      return $resultRedirect->setPath('*/*/');
    }

    public function TextBoxField($textfields,$customModel){
      $text_field_array = [];
      foreach ($textfields as $_textfield){   
        $text_field_array['label'] = $_textfield['label'];
	      $text_field_array['placeholder'] = $_textfield['placeholder'];
    	  $text_field_array['required'] = $_textfield['required'];
    	  $text_field_array['type'] = $_textfield['type'];
    	  $text_field_array['sortorder'] = $_textfield['sortorder'];
    	  $text_field_array['validation'] = $_textfield['validation'];
    	  $text_field_array['status'] = $_textfield['status'];
    	  $customModel->setData($text_field_array);
    	  $customModel->save();
      }  
    }
    public function TextAreaField($text_area_fields,$customModel){

       foreach ($text_area_fields as $_text_area_field){               
        $text_area_array = [];       
        $text_area_array['label'] = $_text_area_field['label'];
        $text_area_array['placeholder'] = $_text_area_field['placeholder'];
        $text_area_array['required'] = $_text_area_field['required'];
        $text_area_array['type'] = $_text_area_field['type'];
        $text_area_array['sortorder'] = $_text_area_field['sortorder'];
        $text_area_array['status'] = $_text_area_field['status'];
        $customModel->setData($text_area_array);
        $customModel->save();
      }  
    }
    public function DropDownField($dropdownds,$customModel,$optionModel){

      foreach($dropdownds as $_dropdown){
        $dropdown_array = [];
        $dropdown_array['label'] = $_dropdown['label'];
        $dropdown_array['required'] = $_dropdown['required'];
        $dropdown_array['type'] = $_dropdown['type'];
        $dropdown_array['sortorder'] = $_dropdown['sortorder'];                           
        $dropdown_array['status'] = $_dropdown['status'];
        $dropdownds_values = $_dropdown['answers_dynamic7'];
        $customModel->setData($dropdown_array);
        $customModel->save();
        $f_id = $customModel->getId();
        foreach($dropdownds_values as $_dropdownds_value){
          $dropdown_values_array = [];    
          $dropdown_values_array['field_id'] = $f_id;
          $dropdown_values_array['value'] = $_dropdownds_value['value']; 
          $optionModel->setData($dropdown_values_array);
          $optionModel->save();
         }                            
      }    
    }
    public function CheckBoxField($checkboxs,$customModel,$optionModel){
      $checkbox_array = [];
      foreach($checkboxs as $_checkbox){                        
        $checkbox_array['label'] = $_checkbox['label'];
	      $checkbox_array['required'] = $_checkbox['required'];
	      $checkbox_array['type'] = $_checkbox['type'];
	      $checkbox_array['sortorder'] = $_checkbox['sortorder'];
	      $checkbox_array['status'] = $_checkbox['status'];
	      $checkboxs_values = $_checkbox['answers_dynamic_checkbox'];
	      $customModel->setData($checkbox_array);
	      $customModel->save();
	      $f_id = $customModel->getId();
        foreach($checkboxs_values as $_checkbox_value){
          $checkbox_values_array = [];    
          $checkbox_values_array['field_id'] = $f_id;
	        $checkbox_values_array['value'] = $_checkbox_value['value']; 
          $optionModel->setData($checkbox_values_array);
          $optionModel->save();
        } 
      }
    }
    public function RadioBoxField($radiofields,$customModel,$optionModel){
      $radiobox_array = [];
      $radiobox_values_array = []; 
      foreach($radiofields as $_radiofield) {  
        $radiobox_array['label'] = $_radiofield['label'];
        $radiobox_array['required'] = $_radiofield['required'];
        $radiobox_array['type'] = $_radiofield['type'];
        $radiobox_array['sortorder'] = $_radiofield['sortorder'];                           
        $radiobox_array['status'] = $_radiofield['status'];
        $radioboxs_values = $_radiofield['answers_dynamic_radio'];
        $customModel->setData($radiobox_array);
        $customModel->save();
        $f_id = $customModel->getId();
        foreach($radioboxs_values as $_radiobox_value) {
          $radiobox_values_array['field_id'] = $f_id;
          $radiobox_values_array['value'] = $_radiobox_value['value']; 
          $optionModel->setData($radiobox_values_array);
          $optionModel->save();
        } 
      }
    }
    public function MultiselectField($multiselect_fields,$customModel,$optionModel){
      $multiselect_array = [];
      $multiselect_values_array = []; 
      foreach($multiselect_fields as $_multiselect_field) {    
        $multiselect_array['label'] = $_multiselect_field['label'];
        $multiselect_array['required'] = $_multiselect_field['required'];
        $multiselect_array['type'] = $_multiselect_field['type'];
        $multiselect_array['sortorder'] = $_multiselect_field['sortorder'];
        $multiselect_array['status'] = $_multiselect_field['status'];
        $multiselect_values = $_multiselect_field['answers_dynamic9'];
        $customModel->setData($multiselect_array);
        $customModel->save();
        $f_id = $customModel->getId();
        foreach($multiselect_values as $_multiselect_value) {
          $multiselect_values_array['field_id'] = $f_id;
          $multiselect_values_array['value'] = $_multiselect_value['value']; 
          $optionModel->setData($multiselect_values_array);
          $optionModel->save();
        }                            
      }                        
    }
    public function UploadField($uploadfields,$customModel){
      $uploadfield_array = [];
      foreach($uploadfields as $_uploadfield) {      
        $uploadfield_array['label'] = $_uploadfield['label'];
        $uploadfield_array['required'] = $_uploadfield['required'];
        $uploadfield_array['type'] = $_uploadfield['type'];
        $uploadfield_array['file_size'] = $_uploadfield['file_size'];			
        $uploadfield_array['sortorder'] = $_uploadfield['sortorder'];  
        $uploadfield_array['status'] = $_uploadfield['status'];                         
        $customModel->setData($uploadfield_array);
        $customModel->save();
      }
    }
    public function DateField($datefields,$customModel){
      $datefield_array = [];
      foreach($datefields as $_datefield) {                            
        $datefield_array['label'] = $_datefield['label'];
        $datefield_array['required'] = $_datefield['required'];
        $datefield_array['type'] = $_datefield['type'];
	      $datefield_array['sortorder'] = $_datefield['sortorder'];
        $datefield_array['status'] = $_datefield['status'];
        $customModel->setData($datefield_array);
        $customModel->save();
      }
    }
    public function Delete_Save($optionsData,$customModel,$optionModel,$opModel){
      foreach ($optionsData as $_optData) {
        $optModel = $opModel->load($optionsData['id'],'field_id');   
        $optModel->delete();     
      }   
      $i=0;
      $optionsArray = []; 
      foreach ($optionsData['value'] as $_optData) {
        $optionsArray['field_id'] = $optionsData['id'];
        $optionsArray['label'] = $optionsData['label'];
        $optionsArray['sortorder'] = $optionsData['sortorder'];
        $optionsArray['required'] = $optionsData['required'];
        $optionsArray['status'] = $optionsData['status'];
        $optionsArray['value'] = $optionsData['value'][$i]; 
        $i++;               
        $optionModel->setData($optionsArray);
        $customModel->setData($optionsArray);
        $customModel->save();
        $optionModel->save();             
      } 
    }
  }
