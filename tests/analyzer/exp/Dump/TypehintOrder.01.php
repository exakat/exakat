<?php

$expected     = array(
    array
        (
            'host' => '\foo1',
            'argument' => '\a',
            'returned' => '\b',
        ),

    array
        (
            'host' => '\foo2',
            'argument' => '\d',
            'returned' => '\b',
        ),

    array
        (
            'host' => '\foo2',
            'argument' => '\b',
            'returned' => '\b',
        ),
                     );

$expected_not = array(
    array
        (
            'host' => '\foo133',
            'argument' => '\a',
            'returned' => '\b',
        ),
                     );

?>