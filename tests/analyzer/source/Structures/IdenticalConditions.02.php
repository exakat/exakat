<?php

// triple
if ($a || $a || $c) {}

if ($a || $c || $a) {}
if ($c || $a || $a) {}

if ($a || ($c || $a)) {}

if ($c && $a && $b) {}


?>