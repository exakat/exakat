<?php

function foo() {
    if (rand(1)) {
        return function () {};
    } else {
        return false;
    }
}

function bar() {
    if (rand()) {
        return true;
    } else {
        return false;
    }
}
?>