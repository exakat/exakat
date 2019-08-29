<?php

const A = [1, "A" => 2,3];
const B = 1;
const C = "D";
const D = [1, "333" => 2,3];

var_dump(...[1, -33 => 2,3]);
var_dump(...[1, "-33" => 2,3]);
var_dump(...[1, "A" => 2,3]);
var_dump(...[1, B => 2,3]);
var_dump(...[1, C => 2,3]);

var_dump(...A);
var_dump(...D);
var_dump(...\A);
var_dump(...\D);

?>