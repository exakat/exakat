<?php


class xxx extends xx {
    function xa($a) {}
    function xb($b) {}
    function xc(...$c) {}
    function xd(...$d) {}
}


class xx extends x {
    function xb2($b) {}
    function xc2(...$c) {}
}

class x {
    function xa($a) {}
    function xb(...$b) {}
    function xc($c) {}
    function xd(...$d) {}

    function xb2(...$b) {}
    function xc2($c) {}
}

?>
