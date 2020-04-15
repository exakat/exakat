<?php

        function test($a = [], $b) {}       // Deprecated
        function test2($a = null, $b) {} // Deprecated
        function test3(Foo $a = null, $b) {} // Allowed
?>