<?php

use Zend\Mvc\Controller\AbstractActionController;

class myController extends AbstractActionController
{

    public function indexAction() {
        if ($wrong) {
            echo $errorMessage;
        }
        
        $view = new ViewModel(array(
            'message' => 'Hello world',
        ));
        $view->setTemplate('view.phtml');
        return $view;    
    }
}

?>