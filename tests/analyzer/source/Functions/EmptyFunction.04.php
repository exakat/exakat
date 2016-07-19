<?php

function ThisIsEmpty(){}
function ThisIsNotEmpty(){ $a; }

// Closure ? 
function (){  };

class a {
    function maSurcharged() {}
    function macSurcharged() { } 
    function maEmpty() {}
}

class b extends a {
    function maSurcharged() { } 
    function mabSurcharged() { } 
    function mbEmpty() {}
}

class c extends b {
    function macSurcharged() { } 
    function mabSurcharged() { } 
    function mcEmpty() {}
}

?>