<?php

$x = "$a[constantasstring]"; // will use string
print $x;
$x = "{$a['string']}"; // OK.
print $x;
$x = "{$a[constantwithincurly]}"; // will use constant.
print $x;
$x = "${a[constantwithindollarcurly]}"; // will use constant if defined.
print $x;

?>