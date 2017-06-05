<?php

// Can find, delimiter is a variable
preg_match("/asdf$a".$d."/isw", $c, $b);

// Can't find, delimiter is a variable
preg_match("{$a}asdf".$d."/is", $c, $b);
preg_match("{$a}asdf".$d."/isw", $c, $b);

?>