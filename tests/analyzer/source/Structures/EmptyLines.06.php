<?php

abstract class foo {
     abstract protected function execute();
     function bar() {}
     abstract protected function execute2();
}

interface bar {
    function foo();
}

$a = function ($a) { $a++; };;