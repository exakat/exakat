<?php

$expected     = array('return $this->foo2( )',
                     );

$expected_not = array('return $a->b( )',
                      'return $x->foo()',
                      'return $x->foo2()',
                     );

?>