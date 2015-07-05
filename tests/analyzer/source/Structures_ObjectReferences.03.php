<?php

	function &a() {
	    $a = new stdclass();
	    return $a;
	}
	
	function &b() {
	    return new stdclass();
	}

	var_dump(a());
	var_dump(b());

?>