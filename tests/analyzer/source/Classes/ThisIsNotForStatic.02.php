<?php

class x {
    static function staticMethodUsingThis() {
        self::$property;
        self::methodCall();
        $this->method();
        
        function () { return self::$property; };
    }

    static function staticMethod() {
        self::$property;
        self::methodCall();
        
        function () { return self::$property; };
    }

    function nonStaticMethod() {
        $this->$property;
        $this->methodCall();

        function () { return $this->property; };
    }
}

trait t {
    static function staticTraitMethodUsingThis() {
        self::$property;
        self::methodCall();
        $this->method();
        
        function () { return self::$property; };
    }

    static function staticMethodInTrait() {
        self::$propertyInTrait;
        self::methodCallInTrait();
        
        function () { return self::$propertyInTrait; };
    }

    function nonStaticMethodInTrait() {
        $this->$propertyInTrait;
        $this->methodCallInTrait();

        function () { return $this->propertyInTrait; };
    }
}

function realFunction() {
    $this->shouldnotHappen;
    $this->shouldnotHappen();
}

function realFunctionNoThis() {
    $normal;
    $o->shouldMayHappen();
}

function () { return $this->property; }

?>