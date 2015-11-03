<?php


interface A {
    public function __toString ( );
}

trait T {
    public function __isset ( $a ) {}
}

class C {
    public function __sleep ( ) {}
}

abstract class AC {
    public abstract function __get ( $b );
}

    function __set_state ( ) {}

?>