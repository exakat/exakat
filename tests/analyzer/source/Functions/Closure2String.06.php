<?php

$function = fn ($f) => strtoupper($f);
$o = new x;
$method = fn ()  => $o->b();
$staticmethodcall = fn () => $c::d($a);

$normal = fn ($n) => $n * 2;
function foo ($n) { return $n * 2;};

?>