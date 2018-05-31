<?php

$expected     = array('function foo5(?string $x, ?callable $y) : ?array { /**/ } ',
                      'function foo4(string $x, ?callable $y) : ?array { /**/ } ',
                      'function foo2(?string $x, ?int $y) { /**/ } ',
                      'function foo(?string $x) { /**/ } ',
                      'function foo3(string $x, int $y) : ?array { /**/ } ',
                     );

$expected_not = array('function bar5(?string $x, ?callable $y) : ?array { /**/ } ',
                      'function bar4(string $x, ?callable $y) : ?array { /**/ } ',
                      'function bar2(?string $x, ?int $y) { /**/ } ',
                      'function bar(?string $x) { /**/ } ',
                      'function bar3(string $x, int $y) : ?array { /**/ } ',
                     );

?>