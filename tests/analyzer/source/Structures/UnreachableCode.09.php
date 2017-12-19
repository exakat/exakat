<?php

function foo() {
    assert(defined('static::CONCURENCE'), get_class($this)." is missing \n");
    $a++;
    assert(0, get_class($this)." is missing \n");
    $b++;
}
?>