<?php

function a(array $x) {}
a();
a(3);
a(1,2);

function a2(array $x = array()) {}
a2();
a(23);
a2(21,22);

function a3($x = array()) {}
a3();
a(33);
a3(31,32);

function a4(&$x = 3) {}
a4();
a4($c);
a4($d,42);