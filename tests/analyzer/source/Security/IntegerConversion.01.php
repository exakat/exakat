<?php 

$a = $_GET['a'];
$f = $_get['a'];

if (3 == $a) {  }
if (3 == $f) {  }

if ($a === "3") {  }

if ((int) $a === "3") { }

foo($_POST['d'], $d, $_REQUEST['s']);
function foo($b, $c, $d, $e = null) {
    if ($b == 3) {     }
    if ($c == 3) {     }
    if ($d == "3") {     }
    if ($e == 3) {     }
}

?>
