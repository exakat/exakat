<?php

$expected     = array('function foo2(int $a) : C|D { /**/ } ',
                      'function foo4( ) : C|D|null { /**/ } ',
                      'function foo(int $a) : R { /**/ } ',
                     );

$expected_not = array('function foo3(int $a) : void { /**/ } ',
                      'function foo5(int $a) : C|D|null { /**/ } ',
                      'function foo6(int $a) : C|D|null { /**/ } ',
                     );

?>