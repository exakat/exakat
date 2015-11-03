<?php
class A extends B {
	var $usedVar;
	var $notUsedVar;

	var $usedDefinedVar = 7;
	var $notUsedDefinedVar = 8;

	var $usedVar1, $usedVar2, $usedVar3;
	var $notUsedVar1, $notUsedVar2, $notUsedVar3;

	var $usedDefinedVar1 = 1, $usedDefinedVar2 = 2, $usedDefinedVar3 = 3;
	var $notUsedDefinedVar1 = 4, $notUsedDefinedVar2 = 5, $notUsedDefinedVar3 = 6;

	function __construct($usedVar = "") {
		$this->usedVar = $usedVar;
		$this->usedVar1 = $usedVar;
		$this->usedVar2 = $usedVar;
		$this->usedVar3 = $usedVar;
		$this->usedDefinedVar = $usedVar;
		$this->usedDefinedVar1 = $usedVar;
		$this->usedDefinedVar2 = $usedVar;
		$this->usedDefinedVar3 = $usedVar;
	}
}
?>