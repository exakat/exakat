<?php

class a {
    function b() {}
    function c() {}
    function d() {}
    function e() {}
    function g() {}
}

class b extends a {
    function f() {
        $this->b()->c()->d()->e();
        $this->e()->c()->d()->b();
    }
}
    
?>
