<?php

trait t {
    function g() {}
    function t2() {}
}

trait t2 {
    function f() {}
}

class x {
    use t, t2 { t::G as G; 
        t::t2 insteadof t2;
            }
}

?>