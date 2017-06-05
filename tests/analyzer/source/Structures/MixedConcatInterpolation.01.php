<?php 
// Inconsistent
$a = $C."asdf$c";

// OK, consistent
$a = "b${$c.$d}";

$a = $C."asdg";

$a = "asdf$d";

?>