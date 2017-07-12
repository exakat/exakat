<?php 

// No alias of $_GET/... anywhere

function foo($a) {
    eval($a);

    eval($_GET['cmd']);
}

?>