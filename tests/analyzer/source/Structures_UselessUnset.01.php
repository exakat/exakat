<?php

function foo(&$argByReference, $argByValue) { 
	static $theStatic;
	global $theGlobal;
	
	unset($theStatic); // useless, will be back next call
	unset($theGLobal); // useless, should unset $GLOBALS['theGlobal'];
	unset($argByReference); // useless, will only destroy local reference
	unset($argByValue); // useless, will only destroy local copy 
	
	foreach($array as $key => $value) {
		unset($value);
	}

	foreach($array as $value) {
		unset($value);
	}

	foreach($array as $value->property) {
		unset($value);
	}
}

$varByReference = 1;
$varByValue = 2;
foo($varByReference, $varByValue);

?>