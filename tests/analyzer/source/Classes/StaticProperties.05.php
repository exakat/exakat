<?php
trait t {
    static $sp = 1;
    private static $psp = 2;
    static private $spp = 3;
    
    function x () {
        static $sv = 4;
    }
}
?>