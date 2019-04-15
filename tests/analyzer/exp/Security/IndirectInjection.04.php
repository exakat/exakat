<?php

$expected     = array('shell_exec($e)',
                      'x::foo3($z, 3)',
                      'foo($_GET, $_post, $z)',
                      '$y->foo2($z, 2)',
                     );

$expected_not = array('$y->foo2(1, $z)',
                      'x::foo3(4, $z)',
                     );

?>