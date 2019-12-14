<?php
namespace FME\Contactus\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    protected $actionFactory;
    protected $_response;
   
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \FME\Contactus\Helper\Data $myModuleHelper
    ) {
        $this->actionFactory = $actionFactory;
        $this->_mymoduleHelper = $myModuleHelper;
        $this->_response = $response;
    }

    public function match(\Magento\Framework\App\RequestInterface $request){    
        $identifier = trim($request->getPathInfo(), '/');

        if (strpos($identifier, $this->_mymoduleHelper->pagelink()) !== false) {
            /*
             * We must set module, controller path and action name for our controller class(Controller/Test/Test.php)
             */
            $request->setModuleName('contactus')->setControllerName('front')->setActionName('index');
        } else {
            //There is no match
            return;
        }
        /*
         * We have match and now we will forward action
         */
        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }
}
