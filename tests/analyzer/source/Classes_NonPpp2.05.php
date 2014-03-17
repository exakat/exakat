<?php
abstract class x {

    final static function finalFS() {}
    static final function finalSF() {}

    static $s = 2;
    static private $sp;
    private static $ps;
    
}

function normalFunction() {}
?>