<?php

use A as C;

foo($a);

function foo() : A {
    return gooA($a);
    return gooB($a);
}

function gooA() : A { }

function gooB() : B { }
function gooC() : C { }

?>