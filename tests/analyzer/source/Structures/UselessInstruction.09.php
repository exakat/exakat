<?php
$a = array(1);

$b = array_replace($a);
$c = array_replace($a, $b);
$d = array_replace(...$c); // Not useless!!

$d = array_merge($d); // Not useless, it may reorganize numerical indices
$d = array_merge_recursive($e); // Not useless, it may reorganize numerical indices

?>