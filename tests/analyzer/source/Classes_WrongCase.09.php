<?php

namespace B\C\D {
class a { const c = 2; static $property=3;}
class b { const c = 2; static $property=3;}
}

namespace B\C {

D\A::$property;
D\A::methodcall();
D\A::constante;

D\b::$property;
D\b::methodcall();
D\b::constante;

try {
    $a instanceof D\A;
} catch (D\A $e) {

}

function ya (D\A $a) {}

try {
    $b instanceof D\b;
} catch (D\b $e) {

}

function yb (D\b $a) {}
}

?>