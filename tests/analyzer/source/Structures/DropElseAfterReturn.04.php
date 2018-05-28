<?php

// drop the else
if ($c == 1) {

} elseif ($a) {
    return $a;
} else {
    try {
        doSomething();
    } catch(Exception $e) {
        return $e;
    }
    ++$a;
}

// drop the then
if ($c == 2) {

} elseif ($b) {
    try {
        doSomething();
    } catch(Exception $e) {
        return $e;
    }
} else {
    return $a;
}

// no else
if ($a2) {
    return $a;
} 

// return in else and then
if ($c == 3) {

} elseif ($a3) {
    return $a;
} else {
    $b = doSomething();
    return $b;
}


?>