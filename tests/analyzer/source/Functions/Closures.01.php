<?php

$x = function ($y) { return 2; };


$a = function ($b) use ($x) { return 3; };

function C ($b) { return 4; }

class x {
    function Cx ($b) { return 5; }
}

?>