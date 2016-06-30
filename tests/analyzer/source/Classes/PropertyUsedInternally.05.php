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
		static::$usedVar = $usedVar;
		static::$usedVar1 = $usedVar;
		static::$usedVar2 = $usedVar;
		static::$usedVar3 = $usedVar;
		static::$usedDefinedVar = $usedVar;
		static::$usedDefinedVar1 = $usedVar;
		static::$usedDefinedVar2 = $usedVar;
		static::$usedDefinedVar3 = $usedVar;
	}
}
?>