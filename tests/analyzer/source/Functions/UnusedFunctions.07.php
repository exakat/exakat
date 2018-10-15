<?php

function recursive0() {
    recursive0();
}

recursive1();
function recursive1() {
    recursive1();
}

/// Recursive level 2
function recursivea0() {
    recursiveb0();
}

function recursiveb0() {
    recursivea0();
}

/// Recursive level 2
recursivea1a();
function recursivea1a() {
    recursiveb1a();
}

function recursiveb1a() {
    recursivea1a();
}

/// Recursive level 2
recursivea1b();
function recursivea1b() {
    recursiveb1b();
}

function recursiveb1b() {
    recursivea1b();
}


?>