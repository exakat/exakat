<?php

class A {
    protected $aThis, $aNonThis, $bThis, $bNonThis, $cThis, $cNonThis, $dThis, $dNonThis, $neverUsed;
    
    function b() {
        $this->aThis = $b->aNonThis;
    }
}

class B extends A {
    
    function d() {
        $this->bThis = $b->bNonThis;
    }
}

class C extends B {
    
    function d() {
        $this->cThis = $b->cNonThis;
    }
}

class D {
    
    function de() {
        $this->dThis = $b->dNonThis;
    }
}

?>