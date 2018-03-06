<?php

$a = substr($b, 0, 1);
$a = substr($b, 0, "1");
$a = substr($b, 0, true);
$a = substr($b, 0, -1);
$a = substr($b, 0, -2);
$a = substr($b, 0, +1);

$a = mb_substr($mb_, 0, 1);

$a = $mb->substr($mbm, 0, 1);

?>