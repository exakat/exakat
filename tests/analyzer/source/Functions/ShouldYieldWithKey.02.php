<?php 

iterator_to_array( good1( ) );

function good1() {
	yield from good12();
	yield from good13();
}

function good12() {
	yield 2 => 4;
}

function good13() {
	yield 4 => 4;
}

iterator_to_array( bad1( ) );

function bad1() {
	yield from good13();
	yield from bad12();
}

function bad12() {
	yield 4;
}

iterator_to_array( bad2( ) );

function bad2() {
	yield from bad12();
	yield from bad12();
}

?>