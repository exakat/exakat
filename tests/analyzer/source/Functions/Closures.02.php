<?php

$x = function () { return 2; };


$a = function () use ($x) { return 3; };

interface i {
    function C ($b);
}

trait t {
    function Ct ($b) { return 5; }
}

?>