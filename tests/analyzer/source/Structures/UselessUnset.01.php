<?php

function foo(&$argByReference, $argByValue) { 
	static $theStatic;
	global $theGlobal;
	
	unset($theStatic); // useless, will be back next call
	unset($theGlobal); // useless, should unset $GLOBALS['theGlobal'];
	unset($argByReference); // useless, will only destroy local reference
	unset($argByValue); // useless, will only destroy local copy 
	
	foreach($array as $key => $valuek) {
		unset($valuek);
	}

	foreach($array as $value) {
		unset($value);
	}

	foreach($array as $valuep->property) {
		unset($valuep);                      // useless
		unset($valuep->property);            // OK 
		unset($valuep->property->property2); // OK, if this is an object 
	}

	foreach($array as $key => $valuep2->property) {
		unset($valuep2);                      // useless
		unset($valuep2->property);            // OK 
		unset($valuep2->property->property2); // OK, if this is an object 
	}
}

$varByReference = 1;
$varByValue = 2;
foo($varByReference, $varByValue);

?>