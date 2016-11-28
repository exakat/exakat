<?php

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\Adapter\Digest as AuthAdapter;

class a extends AbstractActionController {
    public function myAction() {}

    protected function protectedAction() {}
    private function privateAction() {}
      function noVisibilityAction() {}

      function otherMethod() {}
}

class b extends AuthAdapter {
    public function myZendAuthAction() {}
}
?>