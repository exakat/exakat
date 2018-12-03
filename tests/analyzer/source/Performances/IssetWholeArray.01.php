<?php

if (isset($a, $a[3])) {}
if (isset($a[4], $a)) {}
if (isset($a[6], $a[5])) {}
if (isset($a[7][8], $a[7])) {}
if (isset($a[9], $a[9][8])) {}

if (isset($a) || isset($a[1])) {}
if (isset($a[2]) || isset($a[2][1])) {}
if (isset($a[2][3]) || isset($a[2][3][1])) {}
if (isset($a[2][3]) || isset($a[2][3][1][7])) {}

if (isset($a) || isset($b[1])) {}

?>