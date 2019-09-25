<?php

use x as x3;

class x {
    function xa($a) : X {}
    function xa2($a) : X {}
    function xa3($a) : X {}
    function xad($d) {}
    function xae($de) {}
}

class xx extends x {
    function xb($b) : X {}
    function xc($c) {}
    function xd($d) {}
//    function xe($e) : Y {} Yielsd a fatal error in PHP 7.3, so not compilable
}

class xxx extends xx {
    function xa($a) : x  {}
    function xa2($a) : \x  {}
    function xa3($a) : x3  {}
    function xb($b) : X  {}
    function xc($c) : X  {}
    function xd($d) : X {}
//    function xe($e) : z {}
}

?>
