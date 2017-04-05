<?php
// 2.3 class
$a = new Zend\Cache\Storage\Adapter\BlackHole();

// a 2.2 class
function foo2( Zend\Http\Client\Cookies $a) {}

// a 2.3 and 2.2 class
function foo (Zend\Authentication\Adapter\AbstractAdapter $a) {}

?>