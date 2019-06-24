<?php

$a instanceof abc;
$r instanceof real;
$r instanceof float;
$s instanceof string;
$m instanceof mixed;

$f instanceof abcC;
$f instanceof abcI;
$f instanceof abcT;

trait abcT {}
interface abcI {}
class abcC {}

?>