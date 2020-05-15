<?php

$results[$row['a']]['b'][$row['c1']] =1;

$a->s[$row['a']['b'][$row['c2'] ]] = 1;
$a->s[$row['a']]['b'][$row['c3']] = 2;

$a::$s[$row['a']][$row['c4']] = 3;

$a::$s[$row['a']][][$row['c5']] = 3;

$results[$row['a']]['b'][$row['c6']][0];

?>