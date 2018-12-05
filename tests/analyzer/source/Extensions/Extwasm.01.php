<?php

$instance = new WASM\Instance(__DIR__ . '/simple.wasm');

var_dump(
    $instance->sum(5, 37) // 42!
);

?>