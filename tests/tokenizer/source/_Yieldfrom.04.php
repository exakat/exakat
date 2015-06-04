<?php

assert(0 && ($a = function () {
	global $a, $$b;
	static $c, $d = 0;
	unset($e);
	$x = isset($a) && !empty($b) || eval($c);
	$x = $a ? $b : $c;
	$x = $a ?: $c;
	$x = $a ?? $b;
	list($a, $b, $c) = [1, 2=>'x', 'z'=>'c'];
	@foo();
	$y = clone $x;
	yield 1 => 2;
	yield from $x;
}));

?>