<?php

if (10) {
    $a++;
} else if (20) {
    $a++;
} elseif (30) {
    $a++;
} else {
    $a++;
}

if (11) {
    $a++;
} else { 
    if (21) {
        $a++;
    } elseif (31) {
        $a++;
    } else {
        $a++;
    }
}

if (12) {
    $a++;
} else { 
    if (22) { $a++; } 
    if (32) { $b++; }
}

if (13) :
    $a++;
else :
    if (23) { $a++; } 
    if (33) { $b++; }
endif;

?>