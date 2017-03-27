<?php

// 2.0 only class
$a = new Zend\Authentication\Adapter\Digest();

// Not a 2.0 class (2.1+)
$b = $d instanceof Zend\Authentication\Adapter\AbstractAdapter;

$c = new Not\Zend\Authentication\Adapter\Callback();
$c = new Not\Zend\Code\Scanner\VariableScanner;

?>