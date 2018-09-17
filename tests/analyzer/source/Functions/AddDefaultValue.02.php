<?php

class x {
function foo() {}

function foo1($x) { $x = 2;}
function foo2($x = null) { $x = 2;}
function foo3($x = 1) { $x = 2;}
function foo4($x) { $x = new X;}
function foo5($x) { $x = foo();}
function foo6($x, $y, $z) { $x = $y;
                            $z = null;
                            $y = CONSTANTE;
                            }
function foo7($x) { $x = CONSTANTE; }
function foo8($x) { $x = 1 + 3; }
}

?>