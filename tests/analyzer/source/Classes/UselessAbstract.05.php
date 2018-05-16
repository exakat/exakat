<?php

abstract class NoMethods{
    private $b = 2;
     function foo() {}
}
class NoMethods2 extends NoMethods {}

abstract class OneMethod{
    abstract function foo();
}
class OneMethod2 extends OneMethod {
    function foo() {}
}

abstract class OneAbstractMethod{
    abstract function foo();
}
class OneAbstractMethod2 extends OneAbstractMethod {
    function foo() {}
}

?>