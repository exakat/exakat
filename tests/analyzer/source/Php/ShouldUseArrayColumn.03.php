<?php

    // Nope, there is a condition
 foreach($a as $b => $c) {
    if ($c) {
        $e[] = $c->f;
    }
 }

    // Nope, there is someting else
 foreach($a2 as $b2 => $c2) {
    ++$a;
     $e[] = $c2->f;
 }

    // Yes
 foreach($a3 as $b3 => $c3) {
     $e[] = $c3->f;
 }

?>