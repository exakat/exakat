<?php

$function = fn ($f) => return strtoupper($f);
$o = new x;
$method = fn => return $o->b();
$staticmethodcall = fn => return $c::d($a);

$normal = fn ($n) => return $n * 2;
function foo ($n) { return $n * 2;};

?>