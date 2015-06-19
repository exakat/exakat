<?php

namespace {
    $x instanceof UndefinedClass3;
    $x instanceof UndefinedTrait3;
    $x instanceof UndefinedInterface3;

    $x instanceof \UndefinedClass3;
    $x instanceof \UndefinedTrait3;
    $x instanceof \UndefinedInterface3;
    
    $x instanceof DefinedClass3;
    $x instanceof DefinedTrait3;
    $x instanceof DefinedInterface3;
    
    $x instanceof \DefinedClass3;
    $x instanceof \DefinedTrait3;
    $x instanceof \DefinedInterface3;
}

namespace {
    class DefinedClass3 { static function method() {}}
    class DefinedClass1 { const constante = 1;}
    class DefinedClass2 { static public $c = null;}
    interface DefinedInterface3 {}
    trait DefinedTrait3 {}
    
}

?>