<?php
function returnInteger(): int {
	return 1;

	return 'foo';

	return $a;

	return "$a";
}

function NotReturnType() {
	return;
	return 2;
	return A;
}

function returnVoid() : void {
	return;
}

?>