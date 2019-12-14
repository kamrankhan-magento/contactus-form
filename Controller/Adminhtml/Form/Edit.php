<?php

namespace FME\Contactus\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    protected $_coreRegistry;
    protected $resultPageFactory;
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
    protected function _initAction(){
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

    public function execute(){
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('field_id');
        $model = $this->_objectManager->create('FME\Contactus\Model\Form');
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This page no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
         // 3. Set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
            $this->_coreRegistry->register('form_edit_registory', $model);
        }
        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->setActiveMenu('FME_Contactus::Contactus');
        $resultPage->addBreadcrumb(__('Contactus'), __('Contactus'));
        $resultPage->addBreadcrumb(__('Manage Submissions'), __('Manage Submissions'));
        $resultPage->getConfig()->getTitle()->prepend(__('Add New Field'));
        return $resultPage;
    }
}
