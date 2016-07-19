<?php

class A extends B {
	protected $usedProtected;
	protected $notUsedProtected;

	protected $usedDefinedProtected = 7;
	protected $notUsedDefinedProtected = 8;

	protected $usedProtected1, $usedProtected2, $usedProtected3;
	protected $notUsedProtected1, $notUsedProtected2, $notUsedProtected3;

	protected $usedDefinedProtected1 = 1, $usedDefinedProtected2 = 2, $usedDefinedProtected3 = 3;
	protected $notUsedDefinedProtected1 = 4, $notUsedDefinedProtected2 = 5, $notUsedDefinedProtected3 = 6;

	function __construct($usedProtected = "") {
		$this->usedProtected = $usedProtected;
		$this->usedProtected1 = $usedProtected;
		$this->usedProtected2 = $usedProtected;
		$this->usedProtected3 = $usedProtected;
		$this->usedDefinedProtected = $usedProtected;
		$this->usedDefinedProtected1 = $usedProtected;
		$this->usedDefinedProtected2 = $usedProtected;
		$this->usedDefinedProtected3 = $usedProtected;
	}
}
?>