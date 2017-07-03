<?php

if ($a == 1) {
    doSomething();
} else {
    throw new \Exception();
}

if ($a == 2) {
    throw new \Exception();
} else {
    doSomething();
}

if ($a == 3) {
    throw new \Exception();
}

if ($a == 4) {
    doSomethingElse();
} else {
    doSomething();
}

?>