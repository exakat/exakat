<?php

function foo() {
    $a = 1;
    
    fn ($a) =>  $a;

    fn ($b) =>  $a;
}

?>