<?php
class A extends B {
	var $usedVar;
	var $notUsedVar;

	function __construct($usedVar = "") {
		$this->usedVar = $usedVar;
	}
}
?>