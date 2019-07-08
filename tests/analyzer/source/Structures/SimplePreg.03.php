<?php

// OK
const A = '/abc\\]/';

preg_match(A,$r);
preg_match('/abc/',function ($x) { }, $b);
preg_replace_callback('/abc/',function ($x) { }, $b);

$r = function ($x) { };
preg_replace_callback('/abc/',$r, $b);

// KO
preg_replace("/abc\063/",$r, $b);
preg_replace('/abc\063/',$r, $b);


?>