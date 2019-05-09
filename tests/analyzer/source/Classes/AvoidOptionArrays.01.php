<?php

class Foo {
    // Distinct arguments, all typehinted if possible
    function __construct(A $a, B $b, C $c, D $d) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }
}

class Bar {
    // One argument, spread over several properties
    function __construct(array $options) {
        $this->a = $options['a'];
        $this->b = $options['b'];
        $this->c = $options['c'];
        $this->d = $options['d'];
    }

    // Not the constructor
    function foobar(array $options) {
        $this->a = $options['a'];
        $this->b = $options['b'];
        $this->c = $options['c'];
        $this->d = $options['d'];
    }
}

?>