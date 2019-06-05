<?php

function foo() : string {}
function foo3() : void { return ; }
function foo4() : ?string { }

foo() ;
foo2();
foo3();
foo4();
foo5();

?>