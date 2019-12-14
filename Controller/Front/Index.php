<?php

namespace FME\Contactus\Controller\Front;

class Index extends \FME\Contactus\Controller\Front
{

    public function execute()
    {
    
        $this->_view->loadLayout();

        $this->_view->renderLayout();
    }
}
