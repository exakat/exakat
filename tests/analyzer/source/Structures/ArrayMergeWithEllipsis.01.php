<?php


array_merge(...$a);
array_merge(...$a1[1]);
array_merge(...$a->m);
array_merge(...$a1::C);
array_merge(...$a1::$C::$D::$E);

a(...$a2::$b::$c::d()::e ?? array());
a(...$a3[1][3][4] ?: array());

$a3 = array(1,2);
//$a4 = array(2);
$a4 =  null;
//a( ...($a4 === null) ? $a3 : array( 1));
array_merge( ...$a1->m ?? array( 1, 4));
array_merge( ...$a2 ?: array( 1, 4)); // sur coalesce
array_merge( ...$a3::C ?: array( 1, 4)); // sur coalesce
array_merge( ...$a4[3] ?: array( 1, 4)); // sur coalesce
array_merge( ...$a5[3][4]); 

function a($b, $c = 3) {
    print_r($b);
    print_r($c);
}
?>