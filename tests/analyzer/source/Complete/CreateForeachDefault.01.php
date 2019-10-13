<?php

function foo() {
    $a = array(1,2,'c' => 3);
    foreach($a as $b) {
        echo $b;
    }

    foreach($a as $c => $d) {
        echo $b;
    }

    foreach(foo() as $c => $d) {
        echo $b;
    }
}

?>