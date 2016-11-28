<?php

use Zend\Mvc\Controller\AbstractActionController;

class foo extends AbstractActionController {}

class foo2 extends \Zend\Mvc\Controller\AbstractActionController {}

class foo3 extends foo {}

class foo4 extends foo3 {}

// Not a controller...
class bar Zend\Mvc\Controller\AbstractActionController {}
?>