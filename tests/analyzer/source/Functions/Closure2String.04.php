<?php

$method = function ($o1) { return $o1->b();};
$method = function ($o2) use ($a) { return $a->b($o2);};
$method = function ($o2a) { return (new A)->b($o2);};
$method = function ($o2b) use ($a) { return $a->b($o2, 3);};
$method = function ($o3) { return foo()->b($o3);};

$staticmethodcall = function ($O1) { return $O1::b();};
$staticmethodcall = function ($O2) { return A::b($O2);};

?>