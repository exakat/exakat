<?php

if (sizeof($a->b) != 0){    
    $c++;
    foreach ($a->b as $c){
    }
}

if (count(B::$a) > 0)   
    foreach (B::$a as $c){
    }

// Nope, not only foreach
if (sizeof($a->b) > 0) {
    $c++;
    foreach ($a->b as $c){
    }
}

// Nope, has ELSE
if (count($a[3]) > 0){    
    foreach ($a[3] as $c){
    }
} else {
    $d++;
}


?>