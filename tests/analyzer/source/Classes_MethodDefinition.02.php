<?php

abstract class y {
    private $private_property;
    
    const CONSTANT_DE_CLASSE = 1;
    
    abstract function alone_method() ;

    abstract protected function protected_method() ;

    abstract static function static_method() ;

    abstract static protected function static_protected() ;
    abstract protected static function protected_static() ;
    abstract public static function public_static() ;
}

?>