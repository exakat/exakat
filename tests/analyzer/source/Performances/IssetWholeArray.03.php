<?php

$a = array();
var_dump(isset($b, $a[$b]));
var_dump(isset($a, $a[3]));
var_dump(isset($b, $a->c[$b]));
var_dump(isset($a->c[$b], $a->c));
var_dump(isset(A::$C[$b], A::$C));

var_dump(isset($a->c[1], $a->c[1][2]));
var_dump(isset(A::$C[1], A::$C[1][2]));

?>