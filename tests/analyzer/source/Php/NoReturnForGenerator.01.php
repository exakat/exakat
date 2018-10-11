<?php

function generatorWithReturn() {
    yield 1;
    return 2;
}

function generator2WithReturn() {
    yield from generatorWithReturn();
    return 2;
}

function generatorWithoutReturn() {
    yield generatorWithReturn();
}

function generator2WithoutReturn() {
    yield from generatorWithReturn();
}

?>