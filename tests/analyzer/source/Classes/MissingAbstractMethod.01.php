<?php

class b extends a {
    use t;
    
    function bar() {}
    function barT() {}
    function bar2() {}
    function bar2T() {}
    function barConcrete() {}
    function barConcreteT() {}
}

abstract class a extends c {
      function fooC()  {}
    abstract function foo() ;
    abstract function bar() ;
    abstract function foo2() ;
    abstract function bar2() ;
    function barConcrete() {}
}

abstract class c {
    abstract function fooC() ;
}

trait t {
    abstract function fooT() ;
    abstract function barT() ;
    function barConcreteT() {}
}