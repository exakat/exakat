<?php

foreach ($OK1 as $b) {
    // drop the else
    if ($a1) {
        break ;
    } else {
        doSomething();
    }
}

foreach ($OK2 as $b) {
    // drop the then
    if ($a2) {
        doSomething();
    } else {
        break;
    }
}

foreach ($OK3 as $b) {
    // no else
    if ($a3) {
        break;
    } 
}

foreach ($OK4 as $b) {
    // return in else and then
    if ($a4) {
        break;
    } else {
        $b = doSomething();
        break;
    }// Nothing after ifthen
}

foreach ($KO1 as $b) {
    // return in else and then
    if ($a5) {
        break;
    } else {
        $b = doSomething();
        break;
    }
    $a++;
}

?>