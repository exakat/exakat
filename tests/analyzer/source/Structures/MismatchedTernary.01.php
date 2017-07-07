<?php
$object = ($type == 'Type') ? new $type() : null;


$result = ($type == 'Addition') ? $a + $b : $a * $b;

$a = 1;
$result = empty($condition) ? $a : 'default value';
$result = empty($condition2) ? $a : getDefaultValue();

?>