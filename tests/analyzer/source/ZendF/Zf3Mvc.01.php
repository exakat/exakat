<?php
namespace ModuleName\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use stcClass as AbstractActionController2;
use Zend\View\Model\ViewModel;

class HelloController extends AbstractActionController
{
    public function worldAction()
    {
        $message = $this->params()->fromQuery('message', 'foo');
        return new ViewModel(['message' => $message]);
    }
}

class HelloController extends AbstractActionController2
{
    public function worldAction()
    {
        $message = $this->params()->fromQuery('message', 'foo');
        return new ViewModel(['message' => $message]);
    }
}