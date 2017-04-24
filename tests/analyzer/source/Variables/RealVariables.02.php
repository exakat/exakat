<?php

static $global;

class x {
    static $xWithDefault = 1;
    static $x = 2;
    static $x2;

    function y(Stdclass $y = null, $yy = 2, Stdclass $yyy) {
        static $xy = 2;
        
        $y = function ($x) { 
            static $staticxy;
            static $staticxywd = 2;
        };
    }
}

trait t {
    static $tWithDefault = 1;
    static $t = 2;
    static $t2;

    function y(Stdclass $yt = null, $yyt = 2, Stdclass $yyyt) {
        static $ty = 2;
        
        $y = function ($t) { 
            static $statictfy;
            static $statictfywd = 2;
        };
    }
}

