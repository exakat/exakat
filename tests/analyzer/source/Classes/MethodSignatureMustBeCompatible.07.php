<?php

class xx extends x {
    function xa(X $a) {}
    function xa2(X|A $a) {}
    function xa3(X $a) {}
    function xa4(X|A $a) {}
    function xa5(A $a) {}
    function xa6(X $a) {}
    function xa7(X|A|Y $a) {}
}

class x {
    function xa(X $a) {}
    function xa2(X|A $a) {}
    function xa3($a) {}
    function xa4(A|X $a) {}
    function xa5(A|X $a) {}
    function xa6(A|X $a) {}
    function xa7(X|A $a) {}
}

?>
