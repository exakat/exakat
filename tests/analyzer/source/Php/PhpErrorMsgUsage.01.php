<?php

function foo() {
    echo $php_errormsg;
}

function foo2() {
    global $php_errormsg;
    echo $php_errormsg;
}

function foo3() {
    echo $GLOBALS['php_errormsg'];
}



?>