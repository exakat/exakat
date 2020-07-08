<?php

function foo(int $a) : R {}

function foo2(int $a) : C|D {}

function foo3(int $a) : void {}

function foo4() : C|D|null {}

interface i {
    function foo6() : C|D|null ;
}

abstract class c  {
    abstract function foo5() : C|D|null ;
}

?>