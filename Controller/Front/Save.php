<?php
namespace FME\Contactus\Controller\Front;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Customer\Api\CustomerRepositoryInterface as CustomerRepository;

class Save extends \Magento\Framework\App\Action\Action
{  
    private $dataPersistor;
    protected $formKeyValidator;
    protected $inlineTranslation;
    protected $storeManager;
    protected $_transportBuilder;
    protected $customerRepository;
    protected $_contactModel;
    protected $_submitModel;
    protected $subscriberFactory;
    private static $_siteVerifyUrl = "https://www.google.com/recaptcha/api/siteverify?";
    private $_secret;
    private static $_version = "php_1.0";
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CustomerRepository $customerRepository,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \FME\Contactus\Helper\Data $myModuleHelper,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \FME\Contactus\Model\ContactFactory $_contactModel,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \FME\Contactus\Model\SubmitFactory $_submitModel
    ) {
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerRepository = $customerRepository;
        $this->subscriberFactory = $subscriberFactory;
        $this->_mymoduleHelper = $myModuleHelper;
        $this->_contactModel = $_contactModel;
        $this->_submitModel = $_submitModel;
        $this->_transportBuilder = $transportBuilder;
        parent::__construct($context);
    }
    public function execute(){
      $error = false;
      $post = $this->getRequest()->getPostValue();
      if (!$post){
        $this->_redirect('*/*/');
        return;
      }      
      try{
        $postObject = new \Magento\Framework\DataObject();
        $postObject->setData($post);
        $submitModel = $this->_objectManager->create('FME\Contactus\Model\Submit'); 
        $contactModel = $this->_objectManager->create('FME\Contactus\Model\Contact');          
        $saveData = [];
        $contactData = [];
        foreach($post as $key=>$value){                
          if($key=="ip"){
            $contactData['ip'] = $value;
          }
          if( $key=="name"){
            $contactData['name'] = $value;
          }
          if($key=="phone"){
            $contactData['phone'] = $value;
          }
          if($key=="email"){
            $contactData['email'] = $value;
          }
          if($key=="subject"){
            $contactData['subject'] = $value;
          }
          if($key=="message"){
            $contactData['message'] = $value;
          } 
        }
        $contactModel->setData($contactData);
        $contactModel->save(); 
        foreach($post as $key=>$value){
          if($key=='fme_contactus' || $key=='hideit' || $key=='submit' || $key=='g-recaptcha-response'){
            continue;
          }
          $f_id = $contactModel->getId();
          $saveData['contact_id'] = $f_id; 
          $saveData['field_label'] = $key;
          $saveData['field_value'] = $value;                        
          $submitModel->setData($saveData);
          $submitModel->save(); 
        }
        foreach($post['fme_contactus'] as $key=>$value){
          if(is_array($value)){
            $saveData['field_label'] = $key;
            $saveData['field_value'] = implode(', ',$value);   
            $submitModel->setData($saveData);
            $submitModel->save();
          }
          if(!is_array($value)){
            $saveData['field_label'] = $key;
            $saveData['field_value'] = $value;   
            $submitModel->setData($saveData);
            $submitModel->save();
          }                     
        }
        if($this->_mymoduleHelper->isCaptchaEnabled()) {
          $captcha = $this->getRequest()->getParam('g-recaptcha-response');
          $secret = $this->_mymoduleHelper->getsecurekey();
          //"6Le9kwgUAAAAAJn2pRWDkbkls26F3SKBJ7hlggtk"; //Replace with your secret key
          /*$response = null;
          $path = self::$_siteVerifyUrl;
          $dataC =  [
          'secret' => $secret,
          'remoteip' => $_SERVER["REMOTE_ADDR"],
          'v' => self::$_version,
          'response' => $captcha
          ];
          $req = "";
          foreach ($dataC as $key => $value) {
          $req .= $key . '=' . urlencode(str_replace('/','',$value)) . '&';
          }
          Cut the last '&'
          $req = substr($req, 0, strlen($req)-1);
          $response = file_get_contents($path . $req);
          $answers = json_decode($response, true);*/
          if (trim($answers ['success']) == true) {
            if ($this->_mymoduleHelper->getreceipt() !='') {
            // $this->inlineTranslation->suspend();
              $transport = $this->_transportBuilder
              ->setTemplateIdentifier($this->_mymoduleHelper->getemailtemplate())
              ->setTemplateOptions(
                [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                )->setTemplateVars(['data' => $postObject])
              ->setFrom($this->_mymoduleHelper->getemailsender())
              ->addTo($this->_mymoduleHelper->getreceipt())
              ->getTransport();
              $transport->sendMessage();
            }
            $contactModel = $this->_contactModel->create();
            $contactModel->setData($post);
            $contactModel->save();
            $this->messageManager->addSuccess($this->_mymoduleHelper->getAlertSuccess());
            $this->_redirect($this->_redirect->getRefererUrl());
            return;
          }else {
          // Dispay Captcha Error
            $error = true;
            throw new \Exception();
          }
        }else{
        ///////////////////// email block
          if ($this->_mymoduleHelper->getreceipt() !='') {
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier($this->_mymoduleHelper->getemailtemplate())
            ->setTemplateOptions(
              [
              'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
              'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
              ]
              )
            ->setTemplateVars(['data' => $postObject])
            ->setFrom($this->_mymoduleHelper->getemailsender())
            ->addTo($this->_mymoduleHelper->getreceipt())
            ->getTransport();
            $transport->sendMessage();
          }
          //////////////////////////////////////////////////// email blocks
          $contactModel = $this->_contactModel->create();
          $contactModel->setData($post);
          $contactModel->save();
          $this->messageManager->addSuccess($this->_mymoduleHelper->getAlertSuccess());
          $this->_redirect($this->_redirect->getRefererUrl());
          return;
        }
      }catch (\Exception $e) {
      // $this->inlineTranslation->resume();
        $this->messageManager->addError(                         
          $this->_mymoduleHelper->getAlertFailure() 
          );
        $this->getDataPersistor()->set('contactus', $post);
        $this->_redirect($this->_redirect->getRefererUrl());
        return;
      }
    }
    private function getDataPersistor(){
      if($this->dataPersistor === null) {
        $this->dataPersistor = ObjectManager::getInstance()
        ->get(DataPersistorInterface::class);
      }
      return $this->dataPersistor;
    }
}
