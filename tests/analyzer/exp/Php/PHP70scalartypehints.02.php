<?php

$expected     = array('function foo70andmore(array $a) : bool { /**/ } ',
                     );

$expected_not = array('function foo56andless(array $a) { /**/ } ',
                      'function foo71andmore(array $a) : iterable { /**/ } ',
                      'function foo72andmore(array $a) : object { /**/ } ',
                     );

?>