<?php

$a = new x;
$a();
$a(1);
$a(1,2);

class x {
    function __invoke($args) {  }
}


?>