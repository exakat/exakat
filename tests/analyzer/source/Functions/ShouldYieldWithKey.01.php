<?php 

iterator_to_array( good1( ) );

function good1() {
	yield from good12();
}

function good12() {
	yield 2 => 4;
}

iterator_to_array( bad1( ) );

function bad1() {
	yield from bad12();
}

function bad12() {
	yield 4;
}

?>