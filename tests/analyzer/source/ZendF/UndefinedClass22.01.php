<?php

// 2.2 class (may be other versions)
$a = new Zend\Authentication\Adapter\DbTable\AbstractAdapter();

// Not a 2.2 class (2.4+)
$b = $d instanceof Zend\Authentication\Adapter\Callback;

$c = new Not\Zend\Authentication\Adapter\DbTable\AbstractAdapter();
$c = new Not\Zend\Authentication\Adapter\Callback;

?>