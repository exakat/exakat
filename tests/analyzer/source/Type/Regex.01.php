<?php

preg_match('/[abc]+/', $string);
preg_replace('/[abd]+/', $string);
preg_replace('/[ab'.'e]+/', $string);
preg_replace("/[ab$fe]+/", $string);

preg_replace_all($f[1], $string);

$a->preg_replace('/[abe]+/', $string);

?>