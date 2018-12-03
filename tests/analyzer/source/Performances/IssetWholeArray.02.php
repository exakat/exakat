<?php

if (isset($c->d, $c->d[3])) {}
if (isset($c->d[4], $c->d)) {}
if (isset($c->d[6], $c->d[5])) {}
if (isset($c->d[7][8], $c->d[7])) {}
if (isset($c->d[9], $c->d[9][8])) {}

if (isset($c->d) || isset($c->d[1])) {}
if (isset($c->d[2]) || isset($c->d[2][1])) {}
if (isset($c->d[2][3]) || isset($c->d[2][3][1])) {}
if (isset($c->d[2][3]) || isset($c->d[2][3][1][7])) {}

if (isset($c->d) || isset($c->b[1])) {}

?>