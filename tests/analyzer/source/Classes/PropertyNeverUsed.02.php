<?php
class A extends B {
	public $usedPublic;
	public $notUsedPublic;

	function __construct($usedPublic = "") {
		$this->usedPublic = $usedPublic;
	}
}
?>