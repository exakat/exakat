<?php

abstract class NoAbstractMethods{
    public function foo() {
        $this->undefinedp = 3;
    }
}

abstract class WithUndefined{
    private $b = 2;
    static function foo() {
        $this->undefinedp = 3;
    }
}

abstract class NoMethods{
    private $b = 2;
    function foo() {
        $this->undefinedp = 3;
    }
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