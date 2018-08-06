<?php

$c = function () use ($a) {
    $b = 'a';
    $b = 'c';

    $c = 'd';
    
    $a .= 'a';
    $a .= 'c';
}
?>