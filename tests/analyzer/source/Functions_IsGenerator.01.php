<?php

function FunctionIsGenerator() {
    yield $i;
}

function FunctionIsNotGenerator() {
    return $i;
}

class x {
function MethodIsGenerator() {
    yield $i;
}

function MethodIsNotGenerator() {
    return $i;
}

}
?>