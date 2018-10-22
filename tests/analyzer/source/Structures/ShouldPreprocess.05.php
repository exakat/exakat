<?php

// Check for arrays
$a = 'C' . $a['b'];

// Check for variables
$a = 'D' . $a;

// Check for variables
$a = explode(',', self::A);

// Check for variables
class B { const A = 'a,b,c,d'; }
$a = explode(',', B::A);

// Check for variables
class B { 
    const A2 = 'a,b,c,d'; 
    
    function foo() {
        $a = explode(',', self::A2);
    }
}

?>