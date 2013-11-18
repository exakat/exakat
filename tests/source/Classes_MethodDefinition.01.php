<?php

abstract class y {
    private $private_property;
    
    const CONSTANT_DE_CLASSE = 1;
    
    function x() {}
    
    abstract function abstract_alone() ;

    abstract protected function abstract_protected() ;
    protected abstract function protected_abstract() ;

    abstract static function abstract_static() ;
    static abstract function static_abstract() ;

    abstract static protected function abstract_static_protected() ;
    abstract protected static function abstract_protected_static() ;
    protected abstract static function protected_abstract_static() ;
}

?>