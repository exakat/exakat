<?php

function generator() : generator {
	yield 1;
	return 'real generator';
}

function generator2() {
	yield from generator();
	return 'real generator from';
}

function NotGenerator() : generator {
	return 'not generator';
}

?>