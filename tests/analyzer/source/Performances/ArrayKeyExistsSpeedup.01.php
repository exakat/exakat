<?php

$foo = [123 => 456];

if (array_key_exists($foo[123])) {
    // do something
}

if (isset($foo[123])) {
    // do something
}

if (isset($foo[123]) || array_key_exists($foo[123])) {
    // do something
}

if (array_key_exists($foo[123]) || isset($foo[123])) {
    // do something
}

?>