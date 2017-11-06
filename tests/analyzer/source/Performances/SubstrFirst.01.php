<?php

    $a = strtolower(substr($string, $offset, $size));
    $a = substr(strtoupper($string), $offset, $size);
    $a = mb_substr(foo(strtoupper($string)), $offset, $size);

    $a = substr('a'.strtoupper($string), $offset, $size);
    $a = substr($string, '1'.$offset, $size);

?>