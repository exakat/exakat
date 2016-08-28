<?php

function ThisIsEmpty(){}
function ThisIsNotEmpty(){ $a; }

// Closure ? 
function (){  };

class ac {
    function maSurcharged() {}
    function macSurcharged() { } 
    function maEmpty() {}
}

class bc extends ac {
    function maSurcharged() { } 
    function mabSurcharged() { } 
    function mbEmpty() {}
}

class cc extends bc {
    function macSurcharged() { } 
    function mabSurcharged() { } 
    function mcEmpty() {}
}

?>