<?php

$expected     = array('public function foo5(?string $x, ?callable $y) : ?array { /**/ } ',
                      'public function foo4(string $x, ?callable $y) : ?array { /**/ } ',
                      'public function foo2(?string $x, ?int $y) { /**/ } ',
                      'public function foo(?string $x) { /**/ } ',
                      'public function foo3(string $x, int $y) : ?array { /**/ } ',
                     );

$expected_not = array('public function bar5(?string $x, ?callable $y) : ?array { /**/ } ',
                      'public function bar4(string $x, ?callable $y) : ?array { /**/ } ',
                      'public function bar2(?string $x, ?int $y) { /**/ } ',
                      'public function bar(?string $x) { /**/ } ',
                      'public function bar3(string $x, int $y) : ?array { /**/ } ',
                     );

?>