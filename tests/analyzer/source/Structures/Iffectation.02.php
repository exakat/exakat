<?php

// One of the logical member is actually constant
if (($z = 1) && ($y == 2)) { $f++; }

// none of the logical member is constant
if (($z = $y) && ($y == 2)) { $f++; }

?>