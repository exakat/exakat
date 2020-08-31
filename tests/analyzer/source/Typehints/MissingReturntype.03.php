<?php
function barS() : string  {
    return rand(1,2) ? 1 : "a";
}

function barI() : int  {
    return rand(1,2) ? 1 : "a";
}

/*
for PHP 7.4 compatibility
function barSI() : int|string  {
    return rand(1,2) ? 1 : "a";
}
*/

function bar()  {
    return rand(1,2) ? 1 : "a";
}

?>