<?php

$method = function ($o1) { return $o1->b();};
$method = function ($o2) use ($a) { return $a->b($o2);};
$method = function ($o3) { return foo()->b($o3);};
$method = function ($o4) { return $this->b($o4);};
$method = function ($o5) { return $this->$o5();};
$method = function ($o6) { return $this->$o6(3);};
$method = function ($o7) { return strtoupper(3);};

?>