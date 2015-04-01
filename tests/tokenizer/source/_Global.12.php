<?php

$foo = new stdclass();
$foo->bar = "a";
$a = 2;
$$foo->bar;  // ($$foo)->bar}
global $$foo->bar; // ${ $foo->bar}

$$a;
global $$a;


$foo = array('bar' => 'Yes');
$Yes = 'a';
print $$foo['bar']; // $a == ${$b['c']} 
print $$foo{'bar'}; // $a == ${$b['c']} 
$b = array('c' => 'Yes'); 
global $$b['c']; // $a == ${$b['c']}
global $$b{'c'}; // $a == ${$b['c']}

?>