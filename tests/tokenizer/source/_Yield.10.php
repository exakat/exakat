<?php
function gen1() {
    yield from [yield 1];
};

function gen2() {
    yield from [yield from [] ];
};

function gen3() {
    yield from [yield from [], yield from [] ];
};

function gen4() {
    yield from [yield from [], strtolower(yield from []) ];
};
