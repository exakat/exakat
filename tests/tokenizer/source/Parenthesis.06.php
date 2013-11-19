<?php
	function a() {
		return (($a->b) ? c($d->e) : f());
	}

	function b() {
		return ((a::b) ? c($d->e) : f());
	}

	function c() {
		return ((a::$b) ? c($d->e) : f());
	}

?>