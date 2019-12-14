<?php
namespace FME\Contactus\Controller\Adminhtml\Form;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use FME\Contactus\Model\Form as ModelForm;

class InlineEdit extends \Magento\Backend\App\Action
{
    /** @var PostDataProcessor */
    protected $dataProcessor;
    protected $FormModel;
    protected $jsonFactory;  
    public function __construct(
        Context $context,
        PostDataProcessor $dataProcessor,
        ModelForm $FormModel,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->dataProcessor = $dataProcessor;
        $this->FormModel = $FormModel;
        $this->jsonFactory = $jsonFactory;
    }
   /* protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('FME_Prodfaqs::topic');
    } */ 
    public function execute(){
    	/** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []); 
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
        	return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        foreach (array_keys($postItems) as $Id) {
        	/** @var \Magento\Cms\Model\Page $page */
            $Field = $this->FormModel->load($Id);
            try{
            	$Data = $this->filterPost($postItems[$Id]);
                $this->validatePost($Data, $Field, $error, $messages);
                $extendedPageData = $Field->getData();
                $this->setFieldData($Field, $extendedPageData, $Data);
                
                $this->FormModel->save($Field);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPageId($Topic, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPageId($Topic, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPageId(
                    $Topic,
                    __('Something went wrong while saving the item.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
    /**
     * Filtering posted data.
     */
    protected function filterPost($postData = []){
        //$pageData = $this->dataProcessor->filter($postData);     
        return $postData;
    }
    /**
     * Validate post data
     */
    protected function validatePost(array $pageData, ModelForm $page, &$error, array &$messages){
        if (!($this->dataProcessor->validate($pageData) && $this->dataProcessor->validateRequireEntry($pageData))) {
            $error = true;
            foreach ($this->messageManager->getMessages(true)->getItems() as $error) {
                $messages[] = $this->getErrorWithId($page, $error->getText());
            }
        }
    }
    /**
     * Add page title to error message
     */
    protected function getErrorWithId(ModelForm $field, $errorText){
        return '[Field ID: ' . $field->getId() . '] ' . $errorText;
    }
    /**
     * Set cms page data
     */
    public function setFieldData(ModelForm $field, array $extendedPageData, array $pageData){
        $field->setData(array_merge($field->getData(), $extendedPageData, $pageData));
        return $this;
    }
}
