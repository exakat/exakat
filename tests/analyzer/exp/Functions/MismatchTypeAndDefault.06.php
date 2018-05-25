<?php

$expected     = array('function foo3(string $a = PHP_VERSION > 1 ? 3 : "b") { /**/ } ',
                      'function foo(string $a = PHP_VERSION > 1 ? \'a\' : 2) { /**/ } ',
                      'function foo4a(string $a = PHP_VERSION > 1 ?? "b") { /**/ } ',
                      'function foo4(string $a = PHP_VERSION > 1 ?? 3) { /**/ } ',
                      'function foo5a(string $a = PHP_VERSION ?: "b") { /**/ } ',
                      'function foo5(string $a = PHP_VERSION ?: 3) { /**/ } ',
                     );

$expected_not = array('function foo2(string $a = PHP_VERSION > 1 ? \'a\' : "b") { /**/ } ',
                     );

?>