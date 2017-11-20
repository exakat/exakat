<?php

$a = array_slice(array_map('foo', $array), 2, 5);
$a = array_map('foo', array_slice($array, 2, 5));

?>