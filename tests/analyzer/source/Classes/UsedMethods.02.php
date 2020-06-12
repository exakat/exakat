<?php

class a {
    function b() : a {}
    function c() : a {}
    function d() : a {}
    function e() : a {}
    function g() : a {}
}

class b extends a {
    function f() {
        $this->b()->c()->d()->e();
        $this->e()->c()->d()->b();
    }
}
    
?>
