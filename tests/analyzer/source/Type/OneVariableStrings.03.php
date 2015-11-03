<?php

$x = "$y[1]";
$x2 = "$y->a";

$z3 = <<<EOT
$y->a
EOT;

$z4 = <<<EOT
$y[a]
EOT;

$z5 = "$a";
$z6 = <<<HOT
$b
HOT;

?>