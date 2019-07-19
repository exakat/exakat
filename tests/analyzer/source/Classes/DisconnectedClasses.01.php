<?php

class A {
    public $pa = 1;
    
    function fooA() {
        
    }
}

class B extends A {
    public $pB = 1;
    
    function fooB() {
        
    }
}

// A1 uses B1
class A1 {
    public $pa = 1;
    
    function fooA() {
        $this->fooB();
    }
}

class B1 extends A1 {
    public $pB = 1;
    
    function fooB() {
        
    }
}

// B2 uses A1
class A2 {
    public $pa = 1;
    
    function fooA() {
    }
}

class B2 extends A2 {
    public $pB = 1;
    
    function fooB() {
        $this->fooA();
    }
}

// A/B2 uses A/B1
class A3 {
    public $pa = 1;
    
    function fooA() {
        $this->fooB();
    }
}

class B3 extends A3 {
    public $pB = 1;
    
    function fooB() {
        $this->fooA();
    }
}

?>  