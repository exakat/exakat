<?php

$string = 'abc';

// \y has no meaning. With X option, this leads to a regex compilation error, and a failed test.
preg_match('/ye\s/S', $string);
preg_match('/ye\y/', $string);
preg_match('/ye\J/', $string);
preg_match('/ye\s/X', $string);

preg_match("/ye\\y/X", $string);
preg_match("/ye\\J/X", $string);
preg_match("/ye\\s/X", $string);

?>
