<?php

class x {
 function a($b, ...$c) {}
 function b2($b, Stdclass...$c2) {}
 function c($d, $e) { $e; }
}

 a($d, ...$e);
?>