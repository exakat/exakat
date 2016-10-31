<?php

// drop the else
if ($a) {
    return $a;
} else {
    doSomething();
}

// drop the then
if ($b) {
    doSomething();
} else {
    return $a;
}

// no else
if ($a2) {
    return $a;
} 

// return in else and then
if ($a3) {
    return $a;
} else {
    $b = doSomething();
    return $b;
}


?>