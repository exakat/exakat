<?php

foreach ($OK1 as $b) {
    // drop the else
    if ($a1) {
        continue ;
    } else {
        doSomething();
    }
}

foreach ($OK2 as $b) {
    // drop the then
    if ($a2) {
        doSomething();
    } else {
        continue;
    }
}

foreach ($OK3 as $b) {
    // no else
    if ($a3) {
        continue;
    } 
}

foreach ($OK4 as $b) {
    // return in else and then
    if ($a4) {
        continue;
    } else {
        $b = doSomething();
        continue;
    }// Nothing after ifthen
}

foreach ($KO1 as $b) {
    // return in else and then
    if ($a5) {
        continue;
    } else {
        $b = doSomething();
        continue;
    }
    $a++;
}

?>