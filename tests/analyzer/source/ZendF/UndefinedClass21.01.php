<?php

// 2.1 class (may be other versions)
$a = new Zend\Authentication\Adapter\DbTable();

// Not a 2.1 class (2.2+)
$b = $d instanceof Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

$c = new Not\Zend\Authentication\Adapter\DbTable();
$c = new Not\Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;

?>