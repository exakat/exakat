<?php

// dynamic calls
$$dnv = new $dnc();
$dnv = $dnf($$dna[1]);

$x = constant('PHP_VERSION');
$xx = constant('Stdclass::VERSION');

$$o1->b = $o2->$c;

$o3->$cm2() + $$o4->cm1();

$c::$p = $c2::$$p2;

$c::$cms2() + ${$o}::cms1();


// non-dynamic calls
$a->b->C();
$d->e()->f;


?>