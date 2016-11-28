<?php

if ($a == 1) {
    doSomething();
} else {
    doSomethingElse();
    return;
}

if ($a == 2) {
    doSomethingElse();
    return;
} else {
    doSomething();
}

if ($a == 3) {
    doSomethingElse();
    return;
}

if ($a == 4) {
    return ; // dead code anyway
    doSomethingElse();
} else {
    doSomething();
}

?>