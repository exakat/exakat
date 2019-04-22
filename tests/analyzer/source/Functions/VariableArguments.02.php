<?php

 function a($b, ...$c) {}
 function b2($b, Stdclass... $c2) {}
 function c($d, $e) { $e; }
 function d($d, $e) { func_num_args(); }
 
 a($d, ...$e);
?>