<?php

function foo() {
    return 1;
}


// foo2()'s return are unused, so will foo()
// Not yet supported
function foo2() {
    return foo();
}

foo2();
?>