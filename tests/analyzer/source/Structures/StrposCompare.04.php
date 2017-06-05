<?php

if ($x = stripos($b, $c)) { $d++; }

if (false === stripos($b2, $c)) { $d++; } // should be reported as compared to 0

if (2 === stripos($b3, $c3)) { $d++; }  // should not be reported as compared to non-0

?>