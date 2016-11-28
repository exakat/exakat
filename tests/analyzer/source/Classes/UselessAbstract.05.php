<?php

abstract class NoMethods{
    const A = 1;
    
    private $b = 2;
}
class NoMethods2 extends NoMethods {}

abstract class OneMethod{
    function foo() {}
}
class OneMethod2 extends OneMethod {}

abstract class OneAbstractMethod{
    abstract function foo();
}
class OneAbstractMethod2 extends OneAbstractMethod {
    function foo() {}
}

?>