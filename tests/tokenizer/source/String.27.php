<?php

class Foo {
    protected $a = 20;

    public function analyze() {
        $a = " $this->a $x->a $_GET->a ";
        $a = " $this[a] $x[a] $_GET[a] ";
        $a = " $this{a} $x->a $_GET->a ";
    }
}

?>
