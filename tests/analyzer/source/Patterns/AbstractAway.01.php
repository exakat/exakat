<?php

function foo() {
    return rand(1,2);
}

function () {
    return rand(1,3);
};

class x {
    function foo() {
        // Valid abstraction
        return rand(1,4);
    }
}


?>