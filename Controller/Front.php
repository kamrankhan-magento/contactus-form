<?php
namespace FME\Contactus\Controller;

use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

abstract class Front extends \Magento\Framework\App\Action\Action
{
    protected $_customerSession;
    
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession
    ) {
        parent::__construct($context);
        $this->_customerSession = $customerSession;
    }
    public function dispatch(RequestInterface $request){
        return parent::dispatch($request);
    }
}
