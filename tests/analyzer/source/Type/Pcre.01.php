<?php

preg_match('/[0-9a-z]+/', $x);
preg_match('#[0-9A-Z]+#is', $y);
preg_match('#[0-8'.'A-Z]+#is', $y);
preg_match('#[0-9\\#A-Z]+#is', $y);

// Not a regex
$b = '[0-9'.'A-Z]+';


?>