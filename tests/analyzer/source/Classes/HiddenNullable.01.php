<?php 

function foo(string $i = null, int $a = null, A $b = null, $c = null, ?A $d = null, $e) {}

function foo2(null|string $i1 = null, int|null $a1 = null, A|NULL $b1 = null, ?string $c1 = null, ?string $d1) {}

function foo3(Anull $i2 = null, NullB $a2 = null, A|NULLC|C $b2 = null) {}

?>
