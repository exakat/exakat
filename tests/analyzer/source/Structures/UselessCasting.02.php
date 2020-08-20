<?php

$b = (string) (trim($a));
$c = (array) ((array_filter($a, $b)));
$d = (bool)  (array_key_exists($a, $b));
$d = (boolean)  (phpinfo());

$e = (double) (imagerotate());
//$g = (real) (imagefttext());
$g = (float) (acosh());
$f = (int) (memory_get_peak_usage());
$g = (integer) (curl_errno());

// This is OK
$h = (bool) (curl_errno());


// This is to be ignored
$i = (integer) ($a->curl_errno());
$j = (integer) (A::curl_errno());
$k = (integer) ($a->$curl_errno());
$l = (integer) (A::$curl_errno());


?>