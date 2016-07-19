<?php
class A extends B {
	static $usedVar;
	static $notUsedVar;

	static $usedDefinedVar = 7;
	static $notUsedDefinedVar = 8;

	static $usedVar1, $usedVar2, $usedVar3;
	static $notUsedVar1, $notUsedVar2, $notUsedVar3;

	static $usedDefinedVar1 = 1, $usedDefinedVar2 = 2, $usedDefinedVar3 = 3;
	static $notUsedDefinedVar1 = 4, $notUsedDefinedVar2 = 5, $notUsedDefinedVar3 = 6;

	function __construct($usedVar = "") {
		self::$usedVar = $usedVar;
		self::$usedVar1 = $usedVar;
		self::$usedVar2 = $usedVar;
		self::$usedVar3 = $usedVar;
		self::$usedDefinedVar = $usedVar;
		self::$usedDefinedVar1 = $usedVar;
		self::$usedDefinedVar2 = $usedVar;
		self::$usedDefinedVar3 = $usedVar;
	}
}
?>