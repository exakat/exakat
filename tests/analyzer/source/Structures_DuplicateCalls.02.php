<?php 

function foo() {
    $b = substr($a, 1, 2);
    $b = $d[substr($a, 1, 2)];
    if (substr($a, 1, 2)) { f(substr($a, 1, 2)); }
    
    $g->substr($a, 1, 2);

    duplicateInTwoFunctions(1,2,3);

}

function bar() {
    duplicateInTwoFunctions(1,2,3);
}

?>