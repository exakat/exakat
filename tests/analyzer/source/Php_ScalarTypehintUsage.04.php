<?php

function a(int &$int = 1, float &$float = 2.0, string &$string = "3", bool &$bool = false){
    (function_exists('b')) ? 'b' : die('d');
}

function b(stdclass &$stdclass = null){
    (function_exists('b')) ? 'b' : die('d');
}
?>
