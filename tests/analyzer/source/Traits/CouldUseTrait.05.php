<?php

trait t {
    function foo1() {}
}

class WithFoo1Public {
    public function foo1() {}
}

class WithFoo1Protected {
    public function foo1() {}
}

class WithFoo1Private {
    public function foo1() {}
}

class WithFoo1None {
    function foo1() {}
}

class WithFoo1Without {
    function foo2() {}
}

?>