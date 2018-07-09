<?php

$expected     = array('function foo72andmore(object $o, iterable $i, bool $b, array $a) { /**/ } ',
                     );

$expected_not = array('function foo71andmore(iterable $i, bool $b, array $a) { /**/ } ',
                      'function foo70andmore(bool $b, array $a) { /**/ } ',
                      'function foo56andless(array $a) { /**/ } ',
                     );

?>