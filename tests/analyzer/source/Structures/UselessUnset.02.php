<?php

function foo(&$argByReference, $argByValue) { 
	static $theStatic;
	global $theGlobal;
	
	(unset) $theStatic; // useless, will be back next call
	(unset) $theGLobal; // useless, should unset $GLOBALS['theGlobal'];
	(unset) $argByReference; // useless, will only destroy local reference
	(unset) $argByValue; // useless, will only destroy local copy 
	
	foreach($array1 as $key1 => $value1) {
		(unset) $value1;
	}

	foreach($array2 as $value2) {
		(unset) $value2;
	}

	foreach($array3 as $value3->property) {
		(unset) $value3;
	}
}

$varByReference = 1;
$varByValue = 2;
foo($varByReference, $varByValue);

?>