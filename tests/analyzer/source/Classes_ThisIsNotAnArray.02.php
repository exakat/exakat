<?php

class Z {
    function x () {
        $this['z'] = $this['z1'];
    }
}

class A extends Z implements \arrayaccess {
    function x () {
        $this['a'] = $this['a1'];
    }
}

class B extends A {
    function x () {
        $this['b'] = $this['b2'];
    }
}

class C extends B {
    function x () {
        $this['c'] = $this['c3'];
    }
}

class D extends C {
    function x () {
        $this['d'] = $this['d4'];
    }
}

?>