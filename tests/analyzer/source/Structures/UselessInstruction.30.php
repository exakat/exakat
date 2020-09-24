<?php

$c = $a ?: null;
$c = $b ?: false;
$c = $b[3] ? C : false;
$c = $b->p ?: TRUE;


const D = false;
const E = null;

$c = $b::p ?: D;
$c = $b::p ?? D;
$c = $b::p ?? E;

?>