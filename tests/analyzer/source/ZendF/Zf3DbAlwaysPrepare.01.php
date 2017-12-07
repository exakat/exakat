<?php

// OK : all is hardcoded, no chance of injetion
$select->from('foo')->where('x = 5');
$select->from('foo')->where(['x' => $v]);

// Wrong
$select->from('foo')->where('x = '.$v);
$select->from('foo')->where("x = $v");



// limit, offset, order, 
?>