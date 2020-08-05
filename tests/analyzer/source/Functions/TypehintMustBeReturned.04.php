<?php

function foo() : float {}
function foo2() { return null; }
function foo3() { return 'c'; }
function foo5() { return ; }

foo() ;
foo2();
foo5();

?>