<?php
namespace FME\Contactus\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use FME\Contactus\Model\Contact;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\RequestInterface;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;
    protected $scopeConfig;
    protected $_transportBuilder;
    protected $_escaper;
    protected $inlineTranslation;
    protected $_dateFactory;
    
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateFactory,
        \FME\Contactus\Helper\Data $myModuleHelper
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->_escaper = $escaper;
        $this->_dateFactory = $dateFactory;
        $this->_mymoduleHelper = $myModuleHelper;
        $this->inlineTranslation = $inlineTranslation;
        parent::__construct($context);
    }
    public function execute(){
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if($data){
            $id = $this->getRequest()->getParam('contact_id');
            if(isset($data['status']) && $data['status'] === 'true'){
                $data['status'] = Block::STATUS_ENABLED;
            }
            if(empty($data['contact_id'])){
                $data['contact_id'] = null;
            }
            $model = $this->_objectManager->create('FME\Contactus\Model\Contact')->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addError(__('This Submission no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            if($data['reply']!=''){
                $data['status'] = 0;
            }
           
            date_default_timezone_set($this->_mymoduleHelper->timezone());
            $rep_time = date("Y-m-d").' '.date("h:i:sa");
            $data['reply_time']= $rep_time;        
            $model->setData($data);
            $this->inlineTranslation->suspend();
            try{
                 /////////// custom code end
                $postObject = new \Magento\Framework\DataObject();
                $postObject->setData($data);         
                $transport = $this->_transportBuilder
                ->setTemplateIdentifier($this->_mymoduleHelper->getemailreplytemplate())
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars(['data' => $postObject])
                ->setFrom($this->_mymoduleHelper->getemailsender())
                ->addTo($data['email'])
                ->setReplyTo($this->_mymoduleHelper->getemailsender())
                ->getTransport();

                $transport->sendMessage();

                    //////////////////// email
                $model->save();
                $this->messageManager->addSuccess(__('Email sent successfully'));
                $this->dataPersistor->clear('fme_contactus');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['contact_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Submission.'));
            }

            $this->dataPersistor->set('fme_contactus', $data);
            return $resultRedirect->setPath('*/*/edit', ['contact_id' => $this->getRequest()->getParam('contact_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

}
