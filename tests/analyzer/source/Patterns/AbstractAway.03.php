<?php

function foo() {
    return new Datetime(1,2);
}

function () {
    return new Datetime(1,3);
};

class x {
    function foo() {
        // Valid abstraction
        return new Datetime(1,4);
    }
}


?>