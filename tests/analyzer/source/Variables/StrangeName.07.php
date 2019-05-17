<?php

    ${42} = ['value'];
    try {
    echo intdiv(${43}, 3);
    } catch(Throwable $e) {}
    echo "Next\n";
    ${true} = ['value'];
    ${rand() % 2 == 0} = ['value'];
    ${[2]} = ['value'];

    ${1.3} = ['value2'];
    ${(bool) 'C'} = ['value3'];
    ${(string) 'D'} = ['value4'];
    $value = 3;

