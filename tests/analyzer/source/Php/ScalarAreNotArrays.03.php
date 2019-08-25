<?php

fooInt(1);
function fooInt($a) {
    $a[1];
}

fooNull(null);
function fooNull($a) {
    $a[1];
}

fooFloat(1.2);
function fooFloat($a) {
    $a[1];
}

fooInteger(1);
function fooInteger($a) {
    $a[1];
}

fooArray(array());
function fooArray($a) {
    $a[1];
}

fooDouble(array());
function fooDouble($a = 2) {
    $a[1];
}

?>