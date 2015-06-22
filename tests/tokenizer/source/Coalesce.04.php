<?php

function foo() {
    echo "executed!", PHP_EOL;
}
var_dump(true ?? foo()); // outputs bool(true), "executed!" does not appear as it short-circuited

?>
