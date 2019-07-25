<?php

function foo(int $x) { 
    echo $x[2]; 
}

function foo7(array $x) { 
    echo $x[7]; 
}

function foo2($x = 2) { 
    echo $x[3]; 
}

function foo3(?A $x) { 
    echo $x[4]; 

    $a = foo4(); 
    echo $a[5]; 
}

function foo4() : int { 
    return 1;
}

function foo5() : ?int {
    return null;
}
$b = foo5(); 
echo $b[6];

?>