<?php

if ($login == $login2) {
    doSomething();
}


// Trying to confirm consistency
if ($login == $login) {
    doSomething();
}


// Works with every operators
if ($object->login( ) !== $object->login()) {
    doSomething();
}

if ($sum >= $sum) {
    doSomething();
}

//
if ($mask && $mask) {
    doSomething();
}

if ($mask || $mask) {
    doSomething();
}
?>