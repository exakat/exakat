<?php
global $baz;
$baz = 'baz';

function foo(&$lostReference, &$keptReference)
{
    $c = 'c';

    $lostReference =& $c;
    $keptReference = $c;
}

function foo2(&$lostReference2, &$keptReference2)
{
    $c = 'c';

    $lostReference2 = &$c[ + 1];
    $keptReference2 = $c + 1;
}

$bar = 'bar';

foo($bar); 

print $bar;

?>