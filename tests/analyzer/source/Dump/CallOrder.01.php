<?php

function fooA () {
    fooB();
    fooC();
}

function fooB () {
    fooC();
    fooA();
}

function fooC () {
}

?>