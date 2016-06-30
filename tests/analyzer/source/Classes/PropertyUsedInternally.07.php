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
		\a::$usedVar = $usedVar;
		\a::$usedVar1 = $usedVar;
		\a::$usedVar2 = $usedVar;
		\a::$usedVar3 = $usedVar;
		\a::$usedDefinedVar = $usedVar;
		\a::$usedDefinedVar1 = $usedVar;
		\a::$usedDefinedVar2 = $usedVar;
		\a::$usedDefinedVar3 = $usedVar;
	}
}
?>