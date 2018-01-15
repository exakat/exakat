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

abstract class AD {
    public function __GET ( $b ) { echo 1;}
}

    function __set_state ( ) {}

?>