<?php

assert((function () {
    match ('foo') {
        'foo', 'bar' => false,
        'baz' => 'a',
        default => 'b',
    };
})());

?>