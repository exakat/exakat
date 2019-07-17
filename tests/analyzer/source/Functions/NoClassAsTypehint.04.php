<?php 

class X {}
interface I {}

function (X $x, I $i, UNKNOWN $u, string $s, \sqlite3 $sq){};
function (\X $x1, \I $i1, \UNKNOWN $u1, string $s1, \sqlite3 $sq){};

?>