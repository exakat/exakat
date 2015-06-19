<?php

$b = 'b';
$o = new stdclass;
const D = 1;
$o->C = D;
$a = array('b' => array('B' => 'o'));

var_dump($$a[$b]['B']->C);
    if ($$a[$b]['B']->C == D) $c = $a[$b]['E'];
