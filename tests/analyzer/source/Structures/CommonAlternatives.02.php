<?php

// No report : we don,t go inside foreach
if ($a) {
    foreach($b as $c) {}
} else {
    foreach($b as $c) {}
}

// Report : $a++
if ($d) {
    foreach($b as $c) {}
    $a++;
} else {
    foreach($b as $c) {}
    $a++;
}

// No report : we don,t go inside foreach
if ($e) {
    switch($b) {
        case 'c' : 
        default: 
    }
} else {
    switch($b) {
        case 'c' : 
        default: 
    }
}

?>