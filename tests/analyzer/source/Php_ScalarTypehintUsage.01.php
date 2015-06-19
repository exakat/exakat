<?php

function a(int $int, float $float, string $string, bool $bool){
    (function_exists('b')) ? 'b' : die('d');
}

function b(stdclass $stdclass){
    (function_exists('b')) ? 'b' : die('d');
}
?>
