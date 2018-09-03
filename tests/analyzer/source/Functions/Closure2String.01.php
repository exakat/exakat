<?php

$function = function ($f) { return strtoupper($f);};
$method = function ($o) { return $o->b();};
$staticmethodcall = function ($c, $a) { return $c::d($a);};

$normal = function ($n) { return $n * 2;};
function foo ($n) { return $n * 2;};

?>