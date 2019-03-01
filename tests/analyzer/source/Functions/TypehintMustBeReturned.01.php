<?php

function foo() : string {}
function foo2() : string { return null; }
function foo3() : ?string { return null; }
function foo4() : ?string { }
function foo5() { return ; }

foo() ;
foo2();
foo3();
foo4();
foo5();

?>