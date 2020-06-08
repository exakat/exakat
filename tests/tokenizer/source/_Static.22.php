<?php

function a(int $to = 2)
{
    global $a, $b, $c;
    static $a, $b = 1, $c;
}

?>
