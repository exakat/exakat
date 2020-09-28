<?php

function foo(mysqli $a, $b) {
    $a->affected_rows;
    $a->ping();

    $b->affected_rows;
    $a->info();
}

function bar() {
    $a = new mysqli_stmt();
    $a->error_list;
    $a->store_result();
    mysqli::poll();

    $b->affected_rows;
    $a->info();
}

?>