<?php 

// No alias of $_GET/... anywhere

function foo($a) {
    $a = $_GET['cmd'];
    eval($a);
}

function foo2($a) {
    EVAL($a);
    
    $b = $_POST['d'];
    $c = $b.' yes '.$a;
    $d = $a.' yes '.$a;
}

?>