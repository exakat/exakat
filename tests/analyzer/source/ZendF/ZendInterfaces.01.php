<?php
                   // This is a zend interface
class X implements Zend\Authentication\Adapter\Http\Exception\ExceptionInterface, 
                   // This is Not a zend interface
                   Not\Zend\Authentication\Adapter\Http\Exception\ExceptionInterface {
}

                       // This is a zend interface
interface Y extends Zend\Authentication\Adapter\Http\ResolverInterface, 
                       // This is Not a zend interface
                       Not\Zend\Authentication\Adapter\Http\ResolverInterface {

}

?>