<?php

class a extends zend_controller_action {
    public function myAction() {}

    protected function protectedAction() {}
    private function privateAction() {}
      function noVisibilityAction() {}

      function otherMethod() {}
}

class b extends Zend_Auth {
    public function myZendAuthAction() {}
}
?>