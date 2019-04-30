<?php

if ($a1 == 1) {
    doSomething();
} elseif ($a1 == 2) {
    doSomething();
} else {
    return;
}

if ($a2 == 2) {
    return;
} elseif ($a2 == 22) {
    return;
} else {
    doSomething();
}

if ($a3 == 3) {
    return;
} else {
    doSomething();
}


if ($a == 3) {
    return;
}

if ($a == 4) {
    doSomethingElse();
} else {
    doSomething();
}

?>