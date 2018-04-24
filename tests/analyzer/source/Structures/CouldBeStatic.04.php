<?php

class foo {
	function a() {
		global $B;
		global $C;

		$B->fireEvent();

		return true;
	}

	function b() {
		global $B;
		global $D;

		$B->fireEvent();
	}
}
?>