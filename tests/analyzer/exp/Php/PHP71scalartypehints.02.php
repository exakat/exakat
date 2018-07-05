<?php

$expected     = array('function foo71andmore(array $a) : iterable { /**/ } ',
                     );

$expected_not = array('function foo70andmore(array $a) : bool { /**/ } ',
                      'function foo56andless(array $a) { /**/ } ',
                      'function foo72andmore(array $a) : object { /**/ } ',
                     );

?>