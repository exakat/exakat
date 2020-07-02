<?php

call_user_func('increment', $a);
call_user_func(array($a, $b), $a);
call_user_func(array($a, 'B'), $a);
call_user_func_array(array('C', $b), $a);
call_user_func(array('C', 'C'), $a);
call_user_func_array(E, $a);
const E = array('C', 'D');

$x = array('C', $b);
call_user_func_array($x, $a);

function foo() : array { return array($a, $z);}
call_user_func_array(foo(), $a);

?>