<?php
class A extends B {
	private $usedVar;
	private $notUsedVar;
	var $nonPrivate;

	function __construct($usedVar = "") {
		$this->usedVar = $usedVar;
		$this->virtualproperty = 1;
	}
}
?>