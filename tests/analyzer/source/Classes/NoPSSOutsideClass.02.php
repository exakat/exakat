<?php

if ($a instanceof static) {}
if ($a instanceof PARENT) {}
if ($a instanceof self) {}

//function foo1 (static $a) {}
//function foo2 (self $a) {}
//function foo3 (parent $a) {}

class foo {

//function foo1 (static $ac) {
function foo2 (self $ac) {
    if ($ac instanceof static) {}
    if ($ac instanceof PARENT) {}
    if ($ac instanceof self) {}
}
function foo3 (parent $ac) {}

}

trait bar {

//function foo1 (static $at) {
function foo2 (self $at) {
    if ($at instanceof static) {}
    if ($at instanceof PARENT) {}
    if ($at instanceof self) {}
}
function foo3 (parent $at) {}

}

?>