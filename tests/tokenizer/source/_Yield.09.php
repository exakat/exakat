<?php
function gen1() {
    yield from [yield 1];
};

function gen2() {
    yield from [yield ];
};

function gen3() {
    yield from [yield, yield ];
};

function gen4() {
    yield from [yield, strtolower(yield) ];
};

?>