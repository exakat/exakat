<?php

function foo() : string|float {}
function foo2() : string|float { return null; }
function foo3() : string|float|null { return null; }
// can't check : this is linted
//function foo4() : string|float|null { return ; }
function foo5() : string|float|null { return 'c';}
function foo6() : string|float|null { return 2.2;}

?>