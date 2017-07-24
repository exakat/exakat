<?php 

$xNoMagic = new class  {
    public $defined = 1;
    
    function y() {
        $this->defined = 1;
        $this->undefined = 2;
        
        $y->undefinedButNotInternal = 3;
        
        // static calls
        x::$y = 2;
    }
};

$xWithMagic = new class {
    public $defined = 1;
    
    function y() {
        $this->defined = 1;
        $this->undefinedButMagic = 2;
        
        $y->undefinedButNotInternal = 3;
        
        // static calls
        x::$y = 2;
    }
    
    function __get($name) {}
};

$xWithMagic2 = new class {
    public $defined = 1;
    
    function y() {
        $this->defined = 1;
        $this->undefinedButMagic = 2;
        
        $y->undefinedButNotInternal = 3;
        
        // static calls
        x::$y = 2;
    }
    
    function __set($name, $value) {}
};

?>