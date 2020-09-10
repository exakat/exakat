<?php

function FooEval() {
    eval($x);
}

function FooInclude() {
    include $x;
}

function FooIncludeOnce() {
    include_once $x;
}

function FooRequire() {
    require $x;
}

function FooRequireOnce() {
    require_once $x;
}

function FooExtract() {
    extract($x);
}

function FooExtractInclude() {
    extract($x);
    include $x;
}

function FooAlone() {
    print ($x);
    bar($x);
}


?>