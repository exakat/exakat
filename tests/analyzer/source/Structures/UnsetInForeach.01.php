<?php

$f = new stdclass();
$f->e = 1;
$a = array($f);

foreach($a as $unsetB => $c) {
    unset($unsetB);
}

foreach($a as $unsetArrayB => $c) {
    unset($unsetArrayB[1]);
}

foreach($a as $unsetObjectB => $c) {
    unset($unsetObjectB->b);
}

foreach($a as $b => $unsetC) {
    unset($unsetC);
}

foreach($a as $b => &$unsetRefC) {
    unset($unsetRefC);
}

foreach($a as $b => &$unsetArrayC) {
    unset($unsetArrayC[1]);
}

foreach($a as $b => $unsetArrayC2) {
    unset($unsetArraysC[1]);
}

foreach($a as $b => $unsetPropC2) {
    unset($unsetPropC2->a); // this is an object so a reference
}

foreach($a as $b => $c) {
    unset($other);
}

foreach($a as $unsetC) {
    unset($unsetC);
}

foreach($a as &$unsetRefC) {
    unset($unsetRefC);
}

foreach($a as &$unsetArrayC) {
    unset($unsetArrayC[1]);
}

foreach($a as $unsetArrayC2) {
    unset($unsetArraysC[1]);
}

foreach($a as $unsetPropC2) {
    unset($unsetPropC2->a); // this is an object so a reference
}

foreach($a as $c) {
    unset($other);
}

?>