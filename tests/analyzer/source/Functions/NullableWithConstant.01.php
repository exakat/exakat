<?php 
const CONST_RESOLVING_TO_NULL = null;

        // Replace
        function test1(int $arg = CONST_RESOLVING_TO_NULL) {}
        // With
        function test2(?int $arg = CONST_RESOLVING_TO_NULL) {}
        // Or
        function test3(int $arg = null) {}

test1();
test1(null);
//test1('string');
test1(4);

?>