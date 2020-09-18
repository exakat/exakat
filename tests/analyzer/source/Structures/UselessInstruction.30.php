<?php

$c = $a ?: null;
$c = $b ?: false;
$c = $b[3] ? C : false;
$c = $b->p ?: TRUE;
const D = false;

$c = $b::p ?: D;

?>