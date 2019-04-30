<?php

$expected     = array('function foo1(?string $s = null) { /**/ } ',
                      'function foo12(string $s = \\INTEGER) { /**/ } ',
                      'function foo16(string $s = i::ARRAY) { /**/ } ',
                      'function foo14(string $s = i::INTEGER) { /**/ } ',
                      'function foo13(string $s = INTEGER) { /**/ } ',
                     );

$expected_not = array('function foo2(string $s = "1") { /**/ } ',
                      'function foo4(string $s = \'a\' . \'b\') { /**/ } ',
                      'function foo5(string $s = <<<STRING

STRING
) { /**/ } ',
                      'function foo6(string $s = <<<\'STRING\'

STRING
) { /**/ } ',
                     );

?>