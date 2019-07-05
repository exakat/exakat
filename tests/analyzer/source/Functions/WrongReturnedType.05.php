<?php

function returnIterable() : iterable {
	return [];
//	return new ArrayObject();
//	return new class() extends ArrayObject {};

	return new stdclass;
}

function returnCallable() : callable {
	return function () {};
    // test for arrowfunction later
	return 'd';
	return array('a', 'b');
	return array(1,2,3,4);
}

/*
TODO
function returnRelay() : int {
    $array = range(0, 3);

	return count($array);
}
*/
?>