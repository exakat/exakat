<?php

class A { 
    private $AC; 
    private $AB; 
    private $AD = 1; 
    private $none; 
    protected $BA; 
}
class B extends A { 
    private $AB; 
    private $BB; 
    protected $BA; 
}

class C extends B { 
    private $AC; 
}

class D extends C { 
    private $AD; 
}

?>