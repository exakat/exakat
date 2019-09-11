<?php

function foo1(array $a1) {
    $a1 === null;
    $a1 === 1;
    $a1 === 'null';
    $a1 === 3.3;
    $a1 === [3,3,4];

    $a1 !== null;
    $a1 !== 1;
    $a1 !== 'null';
    $a1 !== 3.3;
    $a1 !== [3,3,4];

    $a1 != null;
    $a1 == 1;
    $a1 > 'null';
    $a1 < 3.3;
    $a1 <> [3,3,4];
}

function foo2(?array $a2) {
    $a2 === null;
    $a2 === 1;
    $a2 === 'null';
    $a2 === 3.3;
    $a2 === [3,3,4];

    $a2 !== null;
    $a2 !== 1;
    $a2 !== 'null';
    $a2 !== 3.3;
    $a2 !== [3,3,4];

    $a2 != null;
    $a2 == 1;
    $a2 > 'null';
    $a2 < 3.3;
    $a2 <> [3,3,4];
}

function foo3(array $a3 = null) {
    $a3 === null;
    $a3 === 1;
    $a3 === 'null';
    $a3 === 3.3;
    $a3 === [3,3,4];

    $a3 !== null;
    $a3 !== 1;
    $a3 !== 'null';
    $a3 !== 3.3;
    $a3 !== [3,3,4];

    $a3 != null;
    $a3 == 1;
    $a3 > 'null';
    $a3 < 3.3;
    $a3 <> [3,3,4];
}

function foo4(?array $a4 = null) {
    $a4 === null;
    $a4 === 1;
    $a4 === 'null';
    $a4 === 3.3;
    $a4 === [3,3,4];

    $a4 !== null;
    $a4 !== 1;
    $a4 !== 'null';
    $a4 !== 3.3;
    $a4 !== [3,3,4];

    $a4 != null;
    $a4 == 1;
    $a4 > 'null';
    $a4 < 3.3;
    $a4 <> [3,3,4];
}

?>