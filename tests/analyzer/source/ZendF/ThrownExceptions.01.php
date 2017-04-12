<?php

use \Zend\Filter\Exception\InvalidArgumentException as ExceptionHiddenByAlias;
//All directly thrown exceptions are reported
throw new \RuntimeException('Error while processing');

// Zend exceptions are also reported, thrown or not
$w = new \Zend\Filter\Exception\ExtensionNotLoadedException();
throw $w;
$w = new \Zend\Filter\Exception\ExtensionNotLoadedException;
$w = new ExceptionHiddenByAlias;


$w = new \NotZend\Filter\Exception\ExtensionNotLoadedException();

throw foo();
?>