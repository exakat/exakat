<?php

while ($x = stripos($b, $c)) { $d++; }

while (($x = stripos($b3, $c3)) === true) { $d++; }

do { $something; } while ($y = stripos($b2, $c2));

do { $something; } while (($y = stripos($b4, $c4)) === false);

?>