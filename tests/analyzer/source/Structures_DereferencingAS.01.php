<?php

$a = array(1,2,3);
$y = strtolower($a[3]);

$aFar = array(1,2,3);
$s2 = strtolower($aFar[3]);

$aUnused = array(1,2,3);

$emptyArray = array();
$emptyArray[3] = 2;

$s = "asdfqwer";
$s2 = strtolower($s[3]);


$emptyString = "";
$s2 = $emptyString[3];

?>