<?php

$expected     = array('function foo72andmore(array $a) : object { /**/ } ',
                     );

$expected_not = array('function foo71andmore(array $a) : iterable { /**/ } ',
                      'function foo70andmore(array $a) : bool { /**/ } ',
                      'function foo56andless(array $a) { /**/ } ',
                     );

?>