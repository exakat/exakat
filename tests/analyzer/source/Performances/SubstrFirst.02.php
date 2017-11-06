<?php

    $a = ucfirst(substr($string, $offset, $size));
    $a = substr(mb_string_convert($string), $offset, $size);
    $a = strstr(foo(strtoupper($string)), $offset, $size);

    $a = stristr('a'.strtoupper($string), $offset, $size);
    $a = $a->substr($string, '1'.$offset, $size);

?>