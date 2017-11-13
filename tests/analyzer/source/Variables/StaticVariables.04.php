<?php 

class x {
    static public $staticProperty;

    function y() {
        static $staticVariable5 = 0, $staticVariable6 = 0, $staticVariable7 = 0;

        static $staticVariable2, $staticVariable3, $staticVariable4;
    }
}

trait t {
    static public $staticTraitProperty;
    private $nonStaticProperty = 1;
}

?>