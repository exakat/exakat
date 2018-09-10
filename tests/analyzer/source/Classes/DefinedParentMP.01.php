<?php

class definedClassA extends definedClassC {
    function definedMethod() {}
    private function definedPrivateMethod() {}
    
    const constant1 = 1;
    private const constant2 = 2;

    private $property1 = 1;
    protected $property2 = 1;
    public $property3;

}

class definedClassB extends definedClassA {
     public $x = 2;
     
     function x() {
        parent::undefinedMethod();
        parent::definedPrivateMethod();
        parent::definedMethod();

        parent::class;

        parent::constant1;
        parent::constant2;
        parent::constant3;

        parent::$property1 = 1;
        parent::$property2 = 2;
        parent::$property3 = 3;
        parent::$property4 = 4;
     }
}

?>