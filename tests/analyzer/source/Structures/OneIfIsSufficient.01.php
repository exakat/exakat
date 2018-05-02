<?php
if($a) {
	if($b) {
		++$c;
	}
}
else {
	if($b) {
		++$d;
	}
}

if($a2) {
	if($b1) {
		++$c;
	}
}
else {
	if($b2) {
		++$d;
	}
}

if($a3) {
	if($b3) {
		++$c;
	} else {
		++$d;
	}
}
else {
	if($b3) {
		++$d;
	}
}
	
if($a4) {
	if($b4 === 2) {
		++$c;
	}
}
else {
	if($b4 === 2) {
		++$d;
	} else {
		++$d;
	}
}

?>