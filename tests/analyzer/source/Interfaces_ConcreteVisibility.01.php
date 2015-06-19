<?php

interface i { function interfaceMethod1();
              function interfaceMethod2();
              function interfaceMethod3();
              function interfaceMethod4();
              }

class a implements i {
    private   function interfaceMethod1() {}
    protected function interfaceMethod2() {}
    public    function interfaceMethod3() {}
              function interfaceMethod4() {}
              function classMethod()      {}
}

function notMethod() {}

trait t {
    private   function traitMethod1() {}
}

?>