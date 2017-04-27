<?php

if (intval($c === 1)) {}

// This condition is too deep inside the argument list
if (number_format($c == 2, 3)) {}

?>