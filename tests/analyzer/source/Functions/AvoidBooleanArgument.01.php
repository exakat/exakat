<?php

function foo1($a = true, $b = 1) {}
function foo2($a = false, $b = 1) {}
function foo3($a, $b = \true) {}
function foo4($a, boolean $b) {}
?>