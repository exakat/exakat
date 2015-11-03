<?php

$x = $_GET[1];

f111($x);
f011($z);
f101($z);
f110($z);

function f011() {}
function f111($y) { g($y, $z); }
function f101($y) { g($a, $b); }
function f110($y) { g0($y); }

function g($a) { unlink($a); }
function g0($a) { strtolower($a); }


?>