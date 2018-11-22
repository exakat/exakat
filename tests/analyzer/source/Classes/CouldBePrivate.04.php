<?php

class A {
    static protected $aThis, $aNonThis, $bThis, $bNonThis, $cThis, $cNonThis, $dThis, $dNonThis, $neverUsed;
    
    function b() {
        static::$aThis = static::$aNonThis;
    }
}

class B extends A {
    
    function d() {
        static::$bThis = static::$bNonThis;
    }
}

class C extends B {
    
    function d() {
        static::$cThis = static::$cNonThis;
    }
}

class D {
    // properties are defined in a parent class
    
    function de() {
        static::$dThis = static::$dNonThis;
    }
}

class E {
    // propertie are not defined
    
    function ee() {
        static::$eThis = static::$eNonThis;
    }
}

?>