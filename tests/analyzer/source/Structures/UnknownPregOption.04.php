<?php

// Can find, delimiter is a variable
preg_match("/asdf$a".$d, $c, $b);

// Can't find, delimiter is a variable
preg_match("{$a}asdf".$d, $c, $b);

?>