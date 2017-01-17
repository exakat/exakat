<?php

$a instanceof Zend_Acl_Exception; // In all versions
$a instanceof A\Zend_Logo;        // Not Zend

function a(Zend_Math_BigInteger_Exception $class) {}  // Only in 1.8
function a2(A\Zend_Math_BigInteger_Exception $class) {}  // Only in 1.8

function b(Zend_Http_UserAgent_Features_Adapter_WurflApi $class) {} // only in 1.11
function b2(A\Zend_Http_UserAgent_Features_Adapter_WurflApi $class) {} // only in 1.11

                   // This is a zend interface
$c instanceof Zend\Authentication\Adapter\Http\Exception\ExceptionInterface; 
                   // This is Not a zend interface
$c instanceof Not\Zend\Authentication\Adapter\Http\Exception\ExceptionInterface;

                       // This is a zend interface
function f( Zend\Authentication\Adapter\Http\ResolverInterface $interface = null) {}

                       // This is Not a zend interface
function f2(Not\Zend\Authentication\Adapter\Http\ResolverInterface $interface) {}

?>