<?php

if ($a->b($c === 1)) {}

// This condition is too deep inside the argument list
if (a::c($c == 2, 3)) {}

?>