<?php

$expected     = array('throw new B( )',
                      'throw new C( )',
                     );

$expected_not = array('throw A::$E',
                      'throw $c->d[2]',
                      'throw $a->b',
                     );

?>