<?php
    function test($a = [], $b) {}       
    function test2($a = null, $b) {}    
    function test3(Foo $a = null, $b) {}
    function test4(Foo $a = null) {} 
    function test5(?Foo $a) {} 
?>