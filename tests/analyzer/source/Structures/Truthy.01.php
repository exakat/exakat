<?php

$a1  = "";        // bool(false)
$a8  = "false";   // bool(true)
$a80 = "true";    // bool(true)
$a4  = "foo";     // bool(true)
$a42  = "f$oo";   // bool(true)
$a41 = <<<PHP
PHP;
$a43 = <<<'PHP'
PHP;
$a44 = <<<PHP
sd$f
PHP;
$a45 = <<<'PHP'
dd
PHP;
$a45 = <<<PHP
$sd$f
PHP;

$a10 = 0;         // bool(false)
$a11 = -0;        // bool(false)
$a2  = 1;         // bool(true)
$a3  = -2;        // bool(true)

$a5  = 2.3e5;     // bool(true)
$a6  = 0.0e5;     // bool(false)
$a61 = -0.0e15;   // bool(false)
$a62 = +0.0e15;   // bool(false)
$a63 = 0.01e3;    // bool(false)
$a64 = 0.01e5;    // bool(true)
$a65 = +0.01e3;   // bool(true)

$a6  = array(12); // bool(true)
$a7  = array();   // bool(false)
$a70 = array(array());   // bool(true), then bool(false)
$a62 = [12];      // bool(true)
$a72 = [];        // bool(false)
$a72 = [[]];      // bool(true), then bool(false)

$b1  = true;
$b2  = false;
$b3  = FALSE;
$b4  = False;
$b5  = TRUE;

$b6  = NULL;

// resources are always true

?>