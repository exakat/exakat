<?php

$a = function (array &$a, Stdclass &$b, Callable &$c) {};

$a = function (array &$a2 = array(), Stdclass &$b2 = null, \Stdclass &$b3 = null, Callable &$c2 = null) {};

$a = function (&$b2 = null){};

?>
