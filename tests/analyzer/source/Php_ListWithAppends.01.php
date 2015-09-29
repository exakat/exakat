<?php
list($a[], $a[], $a[]) = [1, 2, 3]; // KO
var_dump($a);

list($a1, $a2, $a3) = [1, 2, 3];     // OK
var_dump($a);

list($a[], $b[], $c[]) = [1, 2, 3];  // OK
var_dump($a);

list($a1[], $a2, $a3) = [1, 2, 3];   // OK
var_dump($a);

list($b[], $c[], $b[], $c[], $b[]) = [1, 2, 3]; // KO
var_dump($a);

?>