<?php
$a = ['-0x1' => 2];
var_dump("$a[-0x1]");
var_dump("$a[-010]");
var_dump("$a[010]");
var_dump("$a[100]");
var_dump("$a[-3]");
var_dump("$a[-00]");

?>