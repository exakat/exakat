<?php
class A extends B {
	private $usedVar = 1;
	private $notUsedVar = 2;
	var $nonPrivate = 3;

	function __construct($usedVar = "") {
		$this->usedVar = $usedVar;
	}
}
?>