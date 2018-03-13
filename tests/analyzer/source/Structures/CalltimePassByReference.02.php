<?php

    function foo($d) {}
    function foo2(&$d) {}

    $c = ab(array(&$d));
    $c = ab([&$e]);
    x(&$b);

?>