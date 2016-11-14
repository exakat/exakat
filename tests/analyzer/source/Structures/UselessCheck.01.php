<?php

if (count($a) > 0){    
    foreach ($a as $c){
    }
}

if (sizeof($a->b) > 0){    
    foreach ($a->b as $c){
    }
}

// Nope, not only foreach
if (sizeof($a->b1) > 0) {
    $c++;
    foreach ($a->b as $c){
    }
}

// Nope, not same array
if (count($ad) > 0){    
    foreach ($d as $c){
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