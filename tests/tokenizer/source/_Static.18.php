<?php

static $inGlobal;

class a {
    public static $ps = 1;
    static public $sp = 2;
    
    function y() {
        static $inFunction = 1;
    }

}
?>