<?php

const A = '3';
const B = ['3'];
define('C', "abc");

foreach($a as $b) {
    $x .= 'd';
}

foreach($a2 as $b2) {
    $x .= A;
}

foreach($a3 as $b3) {
    $x .= \B;
}

foreach($a4 as $b4) {
    $x .= \C;
}

foreach($a5 as $b5) {
    $x .= <<<X
    
X;
}

foreach($a6 as $b6) {
    $x .= <<<'X'
    
X;
}

foreach($a7 as $b7) {
    $x .= D;
}



for($a = 3; $a < 10; ++$a) {
    $x .= 'e';
}

// Not good : foo transforms $a
for($a = 3; $a < 10; ++$a) {
    $x .= foo($a);
}

?>
