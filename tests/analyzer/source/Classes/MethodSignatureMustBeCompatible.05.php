<?php

class x {
    function xa($a) {}
    function xb(...$b) {}
    function xc($c) {}
    function xd(...$d) {}

    function xb2(...$b) {}
    function xc2($c) {}
}

class xx extends x {
    function xb2($b) {}
    function xc2(...$c) {}
}

class xxx extends xx {
    function xa($a) {}
    function xb($b) {}
    function xc(...$c) {}
    function xd(...$d) {}
}

?>
